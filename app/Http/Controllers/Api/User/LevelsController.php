<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Validators\User\LevelValidator;
use App\Repositories\Interfaces\User\LevelRepository;

/**
 * Class LevelsController.
 */
class LevelsController extends Controller
{
    /**
     * @var LevelRepository
     */
    protected $repository;

    /**
     * @var LevelValidator
     */
    protected $validator;

    /**
     * LevelsController constructor.
     * @param LevelRepository $repository
     * @param LevelValidator $validator
     */
    public function __construct(LevelRepository $repository, LevelValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 分销等级列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $levels = $this->repository->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $levels);
    }
    public function payment()
    {
        //检查是否符合升级条件：当前等级是否大于要升级的等级
        //如果付款成功，price1 2 3,4  levelid,
        //根据price1字段对应金额和实际付款金额对比，如果一样升级成功 ，修改用户的level_id和到期时间

    }
}
