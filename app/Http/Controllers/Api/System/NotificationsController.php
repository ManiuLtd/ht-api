<?php

namespace App\Http\Controllers\Api\System;

use Illuminate\Http\Request;
use App\Criteria\UserCriteria;
use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\System\NotificationValidator;
use App\Repositories\Interfaces\System\NotificationRepository;

/**
 * Class NotificationsController.
 */
class NotificationsController extends Controller
{
    /**
     * @var NotificationRepository
     */
    protected $repository;

    /**
     * @var NotificationValidator
     */
    protected $validator;

    /**
     * NotificationsController constructor.
     *
     * @param NotificationRepository $repository
     * @param NotificationValidator $validator
     */
    public function __construct(NotificationRepository $repository, NotificationValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 通知列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $notification = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->pushCriteria(new UserCriteria())
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $notification);
    }
}
