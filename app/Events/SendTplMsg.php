<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SendTplMsg
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 接收人id.
     * @var
     */
    public $openid;

    /**
     * 模板ID.
     * @var
     */
    public $templateId;

    /**
     * 模板内容.
     * @var
     */
    public $data;

    /**
     * 是否为小程序模板消息.
     * @var
     */
    public $isMiniProgram;

    /**
     * 小程序formId.
     * @var
     */
    public $formId;

    /**
     * 跳转页面.
     * @var
     */
    public $page;

    /**
     * 参考文档1：https://www.easywechat.com/docs/master/zh-CN/official-account/template_message
     * 参考文档2：https://www.easywechat.com/docs/master/zh-CN/mini-program/template_message
     * SendTplMsg constructor.
     * @param $openid
     * @param $templateId
     * @param $data
     * @param bool $isMiniProgram
     * @param null $formId
     * @param null $page
     */
    public function __construct($openid, $templateId, $data, bool $isMiniProgram = false, $formId = null, $page = null)
    {
        $this->openid = $openid;
        $this->templateId = $templateId;
        $this->data = $data;
        $this->isMiniProgram = $isMiniProgram;
        $this->formId = $formId;
        $this->page = $page;
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
