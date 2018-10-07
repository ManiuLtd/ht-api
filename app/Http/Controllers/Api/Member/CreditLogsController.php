<?php

namespace App\Http\Controllers\Api\Member;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Member\CreditLogRepository;

/**
 * Class CreditLogsController.
 */
class CreditLogsController extends Controller
{
    /**
     * @var CreditLogRepository
     */
    protected $repository;

    /**
     * CreditLogsController constructor.
     *
     * @param CreditLogRepository $repository
     */
    public function __construct(CreditLogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * //TODO 这是复制后端的  改一下
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
