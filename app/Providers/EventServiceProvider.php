<?php

namespace hiahia\Providers;

use Log;


use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Cache;
use Swoole\Exception;

use hiahia\User;

use hiahia\Notifications\message as Message;
use Illuminate\Notifications\Notification;

const CHECK_IN = 0;
const CHECK_IN_R = 1;
const SEND_MSG = 2;
const SEND_MSG_R = 3;
const PUSH_MSG = 4;
const PUSH_MSG_R = 5;
const PULL_MSG = 6;
const PULL_MSG_R = 7;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'hiahia\Events\SomeEvent' => [
            'hiahia\Listeners\EventListener',
        ],
        'Illuminate\Notifications\Events\NotificationSent' => [
            'hiahia\Listeners\App\Listeners\LogNotification',
        ]
    ];

    public function boot()
    {
        parent::boot();
        \Event::listen('laravoole.requesting', function ($request) {
        });
        \Event::listen('laravoole.requested', function ($request, $response) {
            $ptr = $request->input('protocol');
            $fd = $request->getLaravooleInfo()->fd;
            $response->setContent("");
            Log::info("websocketrequestcoming");
            if (isset($ptr)) {
                switch ($ptr) {
                    case CHECK_IN: {
                        $this->CheckLogin($request);
                        break;
                    }
                    case SEND_MSG: {
                        $message = $request->input("message");
                        $remote = $request->input("remote");
                        $this->SendChatMessage($fd, $message, $remote);
                        break;
                    }
                    case PULL_MSG: {
                        $this->PullChatMessage($request);
                        break;
                    }
                    default: {
                        $this->SendMessage($fd, ['protocol' => $ptr, 'message' => 'default']);
                    }
                }
            }
        });
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    function CheckLogin($request)
    {
        $fd = $request->getLaravooleInfo()->fd;
        Log::info('Login WebsocketChat');
        $rtv = 1;
        try {
            $Token = $request->input('token');
            if ($Token == '') {
                //这里是为了防止作弊逃过验证
                throw new Exception(null, 1);
            }
            $username = $request->input('username');
            $our_token = Cache::tags("mobile_token")->get($username, '');
            if ($our_token == $Token) {
                //通过验证
                //那么这个时候可以把会话和用户名做一个双向绑定
                Cache::tags("user_fd")->forever($username, $fd);
                Cache::tags("fd_user")->forever($fd, $username);
                Log::info("user $username has come with fd:$fd");
            } else {
                throw new Exception(null, 1);
            }
        } catch (Exception $e) {
            //这里出现异常怕都是验证通不过
            $rtv = 0;
            Log::info("A Failed Attempting to $username from fd:$fd");
        } finally {
            $this->SendLoginReturn($fd, $rtv);
            if ($rtv == 0) {
                $this->closeConnection($fd);
            }
        }
    }

    function SendLoginReturn($fd, $status)
    {
        $this->SendMessage($fd, ['protocol' => CHECK_IN_R, 'status' => $status]);
    }

    function SendMessage($fd, $a)
    {
        app('laravoole.server')->push($fd, json_encode($a));
    }

    function closeConnection($fd)
    {
        app('laravoole.server')->close($fd);
    }

    function SendChatMessage($fd, $message, $remote)
    {
        //看是否登录
        $username = Cache::tags("fd_user")->get($fd, '');
        if ($username == '') {
            Log::info(" $username send message on :$fd");
            return $this->SendChatMessageReturn($fd, 1);

        }

        //发送者用户
        $peer_user = User::where("email", '=', $username)->first();

        $sender = $username;
        $text = $message;
        $time = time();

        if ($remote == '') {
            $users = User::all();

            foreach ($users as $user) {
//                if($user->email == $sender)
//                    continue;
                $user->notify(new Message($text, $sender));
            }
            return $this->SendChatMessageReturn($fd, 2);
        } else {
            //获取目标用户
            $remote_user = User::where("email", '=', $remote)->first();

            //没找到
            if ($remote_user == null) {
                return $this->SendChatMessageReturn($fd, 3);
            }

            $remote->notify(new Message($text, $sender));

            return $this->SendChatMessageReturn($fd, 4);
        }

    }

    function SendChatMessageReturn($fd, $status)
    {
        $this->SendMessage($fd, ['protocol' => SEND_MSG_R, 'status' => $status]);
    }

    function PullChatMessage($request)
    {
        $fd = $request->getLaravooleInfo()->fd;
        $username = $this->CheckAuth($request);
        if ($username == '')
            return $this->SendPullMessageReturn($fd, 0, null);
        else {
            $user = User::where('email', '=', $username)->first();
            $notifications = $user->unreadNotifications;
            $notifications->markAsRead();
            $data = json_decode($notifications->reverse()->pluck('data'), true);
            $message = Array();
            foreach ($data as $key => $value) {
                $message[$key] = ['remote' => $value['sender'], 'content' => $value['message']];
            }
            $this->SendPullMessageReturn($fd, 1, $message);
        }
    }

    function CheckAuth($request)
    {
        return Cache::tags("fd_user")->get($request->fd, '');
    }

    function SendPullMessageReturn($fd, $status, $message = null)
    {
        $this->SendMessage($fd, ['protocol' => PULL_MSG_R, 'status' => $status, 'message' => $message]);
    }
}
