<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\OauthRepository;
use App\Validators\System\FeedbackValidator;
use App\Repositories\Interfaces\System\FeedbackRepository;

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
     * @param OauthRepository $repository
     */
    public function __construct(OauthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $backurl = env('APP_URL').'/api/admin/system/callback';
        $tbbackurl = urlencode($backurl.'?type=1');

        $tburi = 'https://oauth.taobao.com/authorize?response_type=code&client_id=23205020&redirect_uri=https%3A%2F%2Fwww.heimataoke.com%2Fuser-authback%3Fbackurl%3D'.$tbbackurl.'%26bind%3DUW&state=1';
        return json(1001,'获取成功',[
            'tb_url' => $tburi,
        ]);
    }

    public function callback()
    {
        $type = request('type', 1);
        $user = getUser();

        switch ($type) {
            case 1:
                $create = [
                    'sid' => request('sid'),
                    'taoid' => request('tao_id'),
                    'name' => request('tao_name'),
                    'auth_time' =>  now()->timestamp(request('auth_time'))->toDateTimeString(),
                    'expire_time' => now()->timestamp(request('expire_time'))->toDateTimeString(),
                    'type' => 1,
                ];
                break;
            case 2:
                $create = [];
                break;
            case 3:
                $create = [];
                break;

            default:
                break;
        }
        $create['user_id'] = $user->id;
        $where = [
            'user_id' => $user->id,
        ];
        if ($type == 1) {
            $where['taoid'] = $create['taoid'];
        }

        $this->repository->updateOrCreate($where,$create);

    }
}
