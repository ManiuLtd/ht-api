<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Validators\Member\CommissionLevelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Member\CommissionLevelUpdateRequest;
use App\Repositories\Interfaces\Member\CommissionLevelRepository;

/**
 * Class CommissionLevelsController.
 */
class CommissionLevelsController extends Controller
{
    /**
     * @var CommissionLevelRepository
     */
    protected $repository;

    /**
     * @var CommissionLevelValidator
     */
    protected $validator;

    /**
     * CommissionLevelsController constructor.
     *
     * @param CommissionLevelRepository $repository
     * @param CommissionLevelValidator $validator
     */
    public function __construct(CommissionLevelRepository $repository, CommissionLevelValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  //TODO 这是复制后端的  改一下
     *  分销等级列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $levels = $this->repository->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $levels);
    }


}
