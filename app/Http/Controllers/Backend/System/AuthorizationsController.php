<?php

namespace App\Http\Controllers\Backend\System;

use Ixudra\Curl\Facades\Curl;
use App\Models\System\Setting;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\System\SettingRepository;
use mysql_xdevapi\Exception;

/**
 * Class FeedbacksController.
 */
class AuthorizationsController extends Controller
{
    /**
     * @var
     */
    protected $repository;

    /**
     * AuthorizationsController constructor.
     * @param SettingRepository $repository
     */
    public function __construct(SettingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
//        //淘宝
//        $tbbackurl = urlencode('http://v2.easytbk.com/api/admin/system/tbcallback?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC92Mi5lYXN5dGJrLmNvbVwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTU0MzMxNTEyMCwiZXhwIjoxOTAzMzE1MTIwLCJuYmYiOjE1NDMzMTUxMjAsImp0aSI6InpyM2JOdFRUaTRXT3E0Rk4iLCJzdWIiOjEsInBydiI6ImI5MTI3OTk3OGYxMWFhN2JjNTY3MDQ4N2ZmZjAxZTIyODI1M2ZlNDgifQ.jtpmkIM94E8ZOZ-jt7EzRNlEOLKKp1sSe8pKI3Ro1n0');

//        $tburi = 'https://oauth.taobao.com/authorize?response_type=code&client_id=23205020&redirect_uri=https%3A%2F%2Fwww.heimataoke.com%2Fuser-authback%3Fbackurl%3D'.$tbbackurl.'%26bind%3DUW&state=1';
//        //京东
//        $jdbackurl = env('APP_URL').'/api/admin/system/jdcallback';
//        $jduri = 'https://oauth.jd.com/oauth/authorize?response_type=code&client_id=57116DD1E5EDBA11B73A251A0BEB739E&redirect_uri='.$jdbackurl;
//        //多多客
//        $ddkbackurl = 'http://v2.easytbk.com/api/admin/system/pddcallback';
//        $ddkuri = 'http://jinbao.pinduoduo.com/open.html?client_id=cdd7fdd7c6164e96b9525f8a9d2d7ddf&response_type=code&redirect_uri='.$ddkbackurl.'&state=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93d3cuaHRhcGkuY25cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE1NDMzMDAzOTUsImV4cCI6MTkwMzMwMDM5NSwibmJmIjoxNTQzMzAwMzk1LCJqdGkiOiIzYkdpTGNsQ0RNd2NzYXVyIiwic3ViIjoxLCJwcnYiOiJiOTEyNzk5NzhmMTFhYTdiYzU2NzA0ODdmZmYwMWUyMjgyNTNmZTQ4In0.si4RM9YFx8woIE9ntzp5DfaPeVeJ3fIAxDcbUAKNAqc';
        $setting = setting(getUserId());

        return json(1001, '获取成功', [
//            'ddkuri' => $ddkuri,
            'taobao'    => $setting->taobao,
            'jingdong'  => $setting->jingdong,
            'pinduoduo' => $setting->pinduoduo,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function TBCallback()
    {

        try {

            $id = getUserId();

            if (!$id) {
                throw new \Exception('用户不存在');
            }


            $create = [
                'sid'         => request('sid'),
                'taoid'       => request('tao_id'),
                'name'        => request('tao_name'),
                'auth_time'   => now()->timestamp(request('auth_time'))->toDateTimeString(),
                'expire_time' => now()->timestamp(request('expire_time'))->toDateTimeString(),
                'type'        => 1,
            ];

            $tbksetting = tbksetting(getUserId());

            \App\Models\Taoke\Setting::query()->where('id', $tbksetting->id)->update([
                'taobao' => json_encode($create),
            ]);

            return json('1001', 'OK');
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 京东授权.
     * @return array
     * @throws \Exception
     */
    public function JDCallback()
    {

        $resp = Curl::to('https://oauth.jd.com/oauth/token')
            ->withData([
                'grant_type' => 'authorization_code',
                'code' => request('code'),
                'redirect_uri' => 'http://www.htapi.cn/api/admin/system/jdcallback',
                'client_id' => '57116DD1E5EDBA11B73A251A0BEB739E',
                'client_secret' => 'dfe23c330ca54ab787e9c5dc699caeaf',
            ])
            ->post();


        $resp = iconv('GBK', 'UTF-8', $resp);

        $resp = json_decode($resp);

        if (! $resp) {
            throw new \Exception('请重新授权');
        }

        return [
            'token' => $resp->access_token,
            'refresh_token' => $resp->refresh_token,
            'taoid' => $resp->uid,
            'name' => $resp->user_nick,
            'expire_time' => now()->addSeconds($resp->expires_in)->toDateTimeString(),
            'type' => 2,
        ];
    }

    /**
     * 拼多多授权回调
     * @return \Illuminate\Http\JsonResponse
     */
    public function PDDCallback()
    {
        try {
            $id = auth()->setToken(request('state'))->id();
            if (!$id) {
                throw new \Exception('用户不存在');
            }
            $resp = Curl::to('http://open-api.pinduoduo.com/oauth/token')
                ->withHeader('Content-Type: application/json')
                ->withData(json_encode([
                    'grant_type' => 'authorization_code',
                    'code' => request('code'),
                    'client_id' => 'cdd7fdd7c6164e96b9525f8a9d2d7ddf',
                    'client_secret' => '6896f97f33c5836f96bc663a708cf85cbde6ee86',
                ]))
                ->post();
            $resp = json_decode($resp);
            if (!$resp) {
                throw new \Exception('请重新授权');
            }

            $pinduoduo = [
                'token' => $resp->access_token,
                'refresh_token' => $resp->refresh_token,
                'taoid' => $resp->owner_id,
                'name' => $resp->owner_name,
                'expire_time' => now()->addSeconds($resp->expires_in)->toDateTimeString(),
                'type' => 3,
            ];
            $tbksetting = tbksetting($id);

            \App\Models\Taoke\Setting::query()->where('id', $tbksetting->id)->update([
                'pinduouo' => json_encode($pinduoduo),
            ]);
            return json('1001', 'OK');
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }
}
