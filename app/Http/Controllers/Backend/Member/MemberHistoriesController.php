<?php

namespace App\Http\Controllers\Backend\Member;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MemberHistoryRepository;
use App\Validators\Member\MemberHistoryValidator;


/**
 * Class MemberHistoriesController.
 *
 * @package namespace App\Http\Controllers;
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
     * 浏览记录
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $memberHistories = $this->repository
            ->pushCriteria (new DatePickerCriteria())
            ->all ();

        return json (1001, '列表获取成功', $memberHistories);

    }
}
