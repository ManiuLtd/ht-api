<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetUserPassword extends Notification
{
    use Queueable;

    /**
     * @var
     */
    protected $token;

    /**
     * ResetUserPassword constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $str = '您的账户于' . date ('Y-m-d H:i:s') . '申请找回密码(登录IP：' . request ()->getClientIp () . ')，若非您本人操作，请忽略此邮件。';


        return (new MailMessage)
            ->subject ('[' . env ('APP_NAME') . ']找回密码')
            ->line ($str)
            ->line ("验证码有效期" . env ('VERIFY_CODE_EXPIRED_TIME') . "秒，您的验证码为：")
            ->action ($this->token, 'javascript:;');

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
            //
        ];
    }
}
