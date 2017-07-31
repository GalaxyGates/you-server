<?php

namespace hiahia\Providers;

use Log;


use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Cache;
use Swoole\Exception;

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
            $ptr = $request->input('ptr');
            $fd = $request->getLaravooleInfo()->fd;

            if (isset($ptr)) {
                switch ($ptr) {
                    case 0: {
                        $rtv = 1;
                        try {
                            $Token = $request->input('Token');
                            if ($Token == '') {
                                //这里是为了防止作弊逃过验证
                                throw new Exception(null, 1);
                            }
                            $username = $request->input('Username');
                            $our_token = Cache::tags("user_token")->get($username, '');
                            if ($our_token == $Token) {
                                //通过验证
                                //那么这个时候可以把会话和用户名做一个双向绑定
                                Cache::tags("user_fd")->put($username, $fd);
                                Cache::tags("fd_user")->put($fd, $username);
                                Log::info("user $username has come with fd:$fd");
                            } else {
                                throw new Exception(null, 1);
                            }
                        } catch (Exception $e) {
                            //这里出现异常怕都是验证通不过
                            $rtv = 0;
                            Log::info("A Failed Attempting to $username from fd:$fd");
                        } finally {
                            $this->SendMessage($request, ['Status' => $rtv]);
                            if ($rtv == 0) {
                                $this->closeConnection($fd);
                            }
                        }
                    }
                    default: {
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

    function SendMessage($fd, $a)
    {
        app('laravoole.server')->push($fd, json_encode($a));
    }

    function closeConnection($fd)
    {
        app('laravoole.server')->close($fd);
    }
}
