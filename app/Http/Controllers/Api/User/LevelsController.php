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

}
