<?php

namespace App\Http\Controllers\Api\System;

use App\Criteria\DatePickerCriteria;
use App\Criteria\MemberCriteria;
use App\Http\Controllers\Controller;
use App\Validators\System\NotificationValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\System\NotificationCreateRequest;
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
     * //TODO 通知列表 可根据类型创建
     * 通知列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $notification = $this->repository
                ->pushCriteria(new DatePickerCriteria())
                ->pushCriteria(new MemberCriteria())
                ->paginate(request('limit', 10));
            return json(1001,'列表获取成功',$notification);
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }
}
