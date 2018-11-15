<?php

namespace App\Http\Controllers\Backend\User;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\User\HistoryValidator;
use App\Repositories\Interfaces\User\HistoryRepository;

/**
 * Class HistoriesController.
 */
class HistoriesController extends Controller
{
    /**
     * @var HistoryRepository
     */
    protected $repository;

    /**
     * @var HistoryValidator
     */
    protected $validator;

    /**
     * HistoriesController constructor.
     *
     * @param HistoryRepository $repository
     * @param HistoryValidator $validator
     */
    public function __construct(HistoryRepository $repository, HistoryValidator $validator)
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
        $userHistories = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $userHistories);
    }
}
