<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendSMS
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 接受短信的手机号
     * @var
     */
    public $phone;

    /**
     * 模板ID
     * @var
     */
    public $template;

    /**
     * 网关
     * @var array
     */
    public $gateways;


    /**
     * 短信内容
     * @var
     */
    public $content;

    /**
     * 验证码
     * @var
     */
    public $code;

    /**
     * SendSMS constructor.
     * @param $phone
     * @param $template
     * @param null $code
     * @param null $content
     * @param array $gateways
     */
    public function __construct($phone, $template, $code = null, $content = null, array $gateways = ['juhe'])
    {
        $this->phone = $phone;
        $this->template = $template;
        $this->code = $code;
        $this->content = $content;
        $this->gateways = $gateways;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
