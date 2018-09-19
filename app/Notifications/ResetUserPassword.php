<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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

        $subject = sprintf ('[%s]找回密码', env ('APP_NAME'));
        $line1 = sprintf ('您的账户于%s申请找回密码(登录IP：%s)，若非您本人操作，请忽略此邮件。', date ('Y-m-d H:i:s'), request ()->getClientIp ());
        $line2 = sprintf ("验证码有效期%s秒，您的验证码为：", env ('VERIFY_CODE_EXPIRED_TIME'));

        return (new MailMessage)
            ->subject ($subject)
            ->line ($line1)
            ->line ($line2)
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
