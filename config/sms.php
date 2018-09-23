<?php

//api文档：https://packagist.org/packages/overtrue/easy-sms

return [
    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
//        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'juhe', 'aliyun',
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        //阿里云
        'aliyun' => [
            'access_key_id' => '',
            'access_key_secret' => '',
            'sign_name' => '',
        ],
        //云片
        'yunpian' => [
            'api_key' => '824f0ff2f71cab52936axxxxxxxxxx',
        ],

        //Submail
        'submail' => [
            'app_id' => '',
            'app_key' => '',
            'project' => '', // 默认 project，可在发送时 data 中指定
        ],
        //螺丝帽
        'luosimao' => [
            'api_key' => '',
        ],
        //容联云通讯
        'yuntongxun' => [
            'app_id' => '',
            'account_sid' => '',
            'account_token' => '',
            'is_sub_account' => false,
        ],
        //互亿无线
        'huyi' => [
            'api_id' => '',
            'api_key' => '',
        ],
        //聚合数据
        'juhe' => [
            'app_key' => env('JUHE_SMS_APP_KEY', ''),
        ],
        //SendCloud
        'sendcloud' => [
            'sms_user' => '',
            'sms_key' => '',
            'timestamp' => false, // 是否启用时间戳
        ],
        //百度云
        'baidu' => [
            'ak' => '',
            'sk' => '',
            'invoke_id' => '',
            'domain' => '',
        ],
        //华信短信平台
        'huaxin' => [
            'user_id' => '',
            'password' => '',
            'account' => '',
            'ip' => '',
            'ext_no' => '',
        ],
        //253云通讯（创蓝）
        'chuanglan' => [
            'account' => '',
            'password' => '',

            // \Overtrue\EasySms\Gateways\ChuanglanGateway::CHANNEL_VALIDATE_CODE  => 验证码通道（默认）
            // \Overtrue\EasySms\Gateways\ChuanglanGateway::CHANNEL_PROMOTION_CODE => 会员营销通道
            'channel' => \Overtrue\EasySms\Gateways\ChuanglanGateway::CHANNEL_VALIDATE_CODE,

            // 会员营销通道 特定参数。创蓝规定：api提交营销短信的时候，需要自己加短信的签名及退订信息
            'sign' => '【通讯云】',
            'unsubscribe' => '回TD退订',
        ],
        //融云
        'rongcloud' => [
            'app_key' => '',
            'app_secret' => '',
        ],
        //天毅无线
        'tianyiwuxian' => [
            'username' => '', //用户名
            'password' => '', //密码
            'gwid' => '', //网关ID
        ],
        //twilio
        'twilio' => [
            'account_sid' => '', // sid
            'from' => '', // 发送的号码 可以在控制台购买
            'token' => '', // apitoken
        ],
        //腾讯云 SMS
        'qcloud' => [
            'sdk_app_id' => '', // SDK APP ID
            'app_key' => '', // APP KEY
            'sign_name' => '', // 短信签名，如果使用默认签名，该字段可缺省（对应官方文档中的sign）
        ],
        //阿凡达数据
        'avatardata' => [
            'app_key' => '', // APP KEY
        ],
    ],
];
