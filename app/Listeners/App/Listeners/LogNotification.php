<?php

namespace hiahia\Listeners\App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Cache;
use Swoole\Exception;
use Log;

use hiahia\User;
use Illuminate\Support\Facades\Notification;

const CHECK_IN = 0;
const CHECK_IN_R = 1;
const SEND_MSG = 2;
const SEND_MSG_R = 3;
const PUSH_MSG = 4;
const PUSH_MSG_R = 5;

class LogNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        //    // $event->channel
        // $event->notifiable
        // $event->notification

        //消息的接收人
        $remote = $event->notifiable;
        $username = $remote->email;
        $fd = Cache::tags("user_fd")->get($username, -1);
        Log::info("sending to :$username on $fd");
        if ($fd == -1) {
            //用户不在线啊
            return;
        } else {
            //推送消息
            $this->PushChatMessage($fd, $event->notification->id, $event->notification->_sender, $event->notification->_message_text, $event->notification->_session);
        }
    }

    function PushChatMessage($fd, $id, $remote, $message, $session)
    {
        $this->SendMessage($fd, ['protocol' => PUSH_MSG,
                'message' => Array(
                    ['id' => $id, 'remote' => $remote, 'content' => $message, 'session' => $session],
                )
            ]
        );
    }

    function SendMessage($fd, $a)
    {
        try {
            app('laravoole.server')->push($fd, json_encode($a));
        } catch (\Exception $e) {
            Log::info('send message exception:' . $e);
        }
    }
}
