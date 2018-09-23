<?php

namespace App\Http\Controllers\Backend\Member;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\Member\MemberHistoryValidator;
use App\Repositories\Interfaces\MemberHistoryRepository;

/**
 * Class MemberHistoriesController.
 */
class MemberHistoriesController extends Controller
{
    /**
     * @var MemberHistoryRepository
     */
    protected $repository;

    /**
     * @var MemberHistoryValidator
     */
    protected $validator;

    /**
     * MemberHistoriesController constructor.
     *
     * @param MemberHistoryRepository $repository
     * @param MemberHistoryValidator $validator
     */
    public function __construct(MemberHistoryRepository $repository, MemberHistoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 浏览记录.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $memberHistories = $this->repository
            ->with('goods')
            ->pushCriteria(new DatePickerCriteria())
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $memberHistories);
    }
}
