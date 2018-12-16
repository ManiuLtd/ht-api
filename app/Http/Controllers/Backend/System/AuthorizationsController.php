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
    public function TBCallback()
    {

        try {

            $id = getUserId ();

            if (!$id) {
                throw new \InvalidArgumentException('用户不存在');
            }


            $insert = [
                'sid' => request ('sid'),
                'taoid' => request ('tao_id'),
                'name' => request ('tao_name'),
                'auth_time' => now ()->timestamp (request ('auth_time'))->toDateTimeString (),
                'expire_time' => now ()->timestamp (request ('expire_time'))->toDateTimeString (),
                'type' => 1,
                'user_id' => $id,
            ];


            \App\Models\Taoke\Setting::query ()->updateOrCreate ([
                'user_id' => $id
            ], [
                'taobao' => $insert,
            ]);

            return json ('1001', 'OK');
        } catch (\Exception $e) {
            return json (5001, $e->getMessage ());
        }
    }


    /**
     * 拼多多授权回调
     * @return \Illuminate\Http\JsonResponse
     */
    public function PDDCallback()
    {
        try {
            $id = auth ()->setToken (request ('state'))->id ();
            if (!$id) {
                throw new \InvalidArgumentException('用户不存在');
            }
            $resp = Curl::to ('http://open-api.pinduoduo.com/oauth/token')
                ->withHeader ('Content-Type: application/json')
                ->withData (json_encode ([
                    'grant_type' => 'authorization_code',
                    'code' => request ('code'),
                    'client_id' => 'cdd7fdd7c6164e96b9525f8a9d2d7ddf',
                    'client_secret' => '6896f97f33c5836f96bc663a708cf85cbde6ee86',
                ]))
                ->post ();
            $resp = json_decode ($resp);
            if (!$resp) {
                throw new \InvalidArgumentException('请重新授权');
            }

            $pinduoduo = [
                'token' => $resp->access_token,
                'refresh_token' => $resp->refresh_token,
                'taoid' => $resp->owner_id,
                'name' => $resp->owner_name,
                'expire_time' => now ()->addSeconds ($resp->expires_in)->toDateTimeString (),
                'type' => 3,
                'user_id' => $id,
            ];


            \App\Models\Taoke\Setting::query ()->updateOrCreate ([
                'user_id' => $id,
            ], [
                'pinduouo' => $pinduoduo,
            ]);
            return json ('1001', 'OK');
        } catch (\Exception $e) {
            return json (5001, $e->getMessage ());
        }
    }
}
