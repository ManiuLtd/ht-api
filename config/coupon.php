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

        'JDM_APP_KEY' => '57116DD1E5EDBA11B73A251A0BEB739E',
        'JDM_APP_SECRET' => '8d05a49c2bad4c1fa62caa78c2647757',

        'JDMEDIA_APPKEY' => '9357e9ece6b6476db183967e7ea5e892',
        'JDMEDIA_SECRETKEY' => '27ed5eb361184677b14e3075a01e1d88',
        'JDMEDIA_WEB_ID' => '29047',

        'access_token' => '177e6693-511f-4678-9161-5d9e9a8bfebd',
        'refresh_token' => '0d6ffc80-19e0-488d-8198-b20de719b9f5',
    ],

    'pinduoduo' => [
        'PDD_CLIENT_ID' => env('PDD_CLIENT_ID', 'cdd7fdd7c6164e96b9525f8a9d2d7ddf'),
        'PDD_CLIENT_SECRET' => env('PDD_CLIENT_SECRET', '6896f97f33c5836f96bc663a708cf85cbde6ee86'),
    ],

    'qingtaoke' => [
        'APP_KEY' => 'NnLRzzZZ',
    ],

//    'PeWQdV';
//        'https://oauth.jd.com/oauth/authorize?response_type=code&client_id=57116DD1E5EDBA11B73A251A0BEB739E&redirect_uri=https://www.iwxapp.com';
//        'https://oauth.jd.com/oauth/token?grant_type=authorization_code&client_id=57116DD1E5EDBA11B73A251A0BEB739E&redirect_uri=https://www.iwxapp.com&code=PeWQdV&client_secret=8d05a49c2bad4c1fa62caa78c2647757';
];
