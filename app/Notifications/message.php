<?php

namespace hiahia\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class message extends Notification
{
    use Queueable;

    //发送文本
    public $_message_text;
    //发送者
    public $_sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($text, $sender)
    {
        $this->_message_text = $text;
        $this->_sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'sender' => $this->_sender,
            'message' => $this->_message_text,
        ];
    }
}
