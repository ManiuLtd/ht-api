 <?php

return [
    'taobao' => [
        'TB_API_KEY' => env('TB_API_KEY', 't2jlw4q2np'),
        'TB_API_URL' => env('TB_API_URL', 'http://api.dataoke.com/index.php'),
    ],

    'jingdong' => [
        'JD_APPID' => env('JD_APPID', '1805030919383801'),
        'JD_APPKEY' => env('JD_APPKEY', 'fac805ea86063176c9369905a8a6fe47'),
        'JD_LIST_APPURL' => env('JD_LIST_APPURL', 'http://japi.jingtuitui.com/api/get_goods_list'),

    ],

    'pinduoduo' => [
        'PDD_CLIENT_ID' => env('PDD_CLIENT_ID', 'cdd7fdd7c6164e96b9525f8a9d2d7ddf'),
        'PDD_CLIENT_SECRET' => env('PDD_CLIENT_SECRET', '6896f97f33c5836f96bc663a708cf85cbde6ee86'),
    ],
];
