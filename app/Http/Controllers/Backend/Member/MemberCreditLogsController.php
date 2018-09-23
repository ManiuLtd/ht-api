<?php

namespace App\Http\Controllers\Backend\Member;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MemberCreditLogRepository;

/**
 * Class MemberCreditLogsController.
 */
class MemberCreditLogsController extends Controller
{
    /**
     * @var MemberCreditLogRepository
     */
    protected $repository;

    /**
     * MemberCreditLogsController constructor.
     *
     * @param MemberCreditLogRepository $repository
     */
    public function __construct(MemberCreditLogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 积分余额日志.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $memberCreditLogs = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->with(['member', 'user'])
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $memberCreditLogs);
    }
}
