<?php

namespace App\Listeners;

use App\Events\SendTplMsg;

class SendTplMsgNotification
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
     * @param  SendTplMsg $event
     * @return void
     */
    public function handle(SendTplMsg $event)
    {
        if ($event->isMiniProgram) {
            if ($event->formId == null || $event->page == null) {
                throw new \InvalidArgumentException('小程序模板消息必须传入formId和page参数');
            }
        }

        try {
            $app = $event->isMiniProgram ? factory ('wechat.mini_program') : factory ('wechat.official_account');
            //消息内容
            $message = [
                'touser' => $event->openid,
                'template_id' => $event->templateId,
                'data' => $event->data

            ];
            //小程序模板消息额外字段
            if ($event->isMiniProgram) {
                $message['page'] = $event->page;
                $message['form_id'] = $event->formId;
            }

            $app->template_message->send ($message);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage ());
        }
    }
}
