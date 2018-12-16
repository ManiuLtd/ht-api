<?php

return [
    'taobao' => [
        'TKJD_API_KEY' => env('TKJD_API_KEY', 'a702d09d248becb575dc798b6e432d88'), //淘客基地
        'TKJD_API_URL' => env('TKJD_API_URL', 'http://api.tkjidi.com'), //淘客基地接口地址

        'HMTK_APP_KEY' => env('HMTK_APP_KEY', '193298714'), //黑马淘客AppKey
        'HMTK_APP_SECRET' => env('HMTK_APP_SECRET', '33591f90704bcfc868871794c80ac185'), //黑马淘客appsecret

        'HDK_APIKEY' => env('HDK_APIKEY', 'poerjewnsjfhsd'), //好单库apikey

    ],

    'jingdong' => [
        'JD_APPID' => env('JD_APPID', '1805030919383801'),
        'JD_APPKEY' => env('JD_APPKEY', 'fac805ea86063176c9369905a8a6fe47'),

        'JDM_APP_KEY' => '57116DD1E5EDBA11B73A251A0BEB739E',
        'JDM_APP_SECRET' => 'dfe23c330ca54ab787e9c5dc699caeaf',

        'JD_HJK_APIKEY' => '8baf004e74c0b239',

        //京东订单获取，JDM_APP_KEY被封
        'JDMEDIA_APPKEY' => '9357e9ece6b6476db183967e7ea5e892',
        'JDMEDIA_SECRETKEY' => '27ed5eb361184677b14e3075a01e1d88',
        'access_token' => 'ae5971e5-50db-4699-9907-872b516d80fe',

    ],

    'pinduoduo' => [
        'PDD_CLIENT_ID' => env('PDD_CLIENT_ID', '74f24c204a994dabb89f6b94c08c48a3'),
        'PDD_CLIENT_SECRET' => env('PDD_CLIENT_SECRET', '92ff1f13aa9be0a166f9513ee25016d34e81fd29'),
    ],

];
