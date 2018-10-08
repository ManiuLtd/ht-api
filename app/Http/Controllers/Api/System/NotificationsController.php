<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use App\Validators\System\NotificationValidator;
use Illuminate\Http\Request;
use Mockery\Exception;
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
     */
    public function index(Request $request)
    {
        try{
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $notification = $this->repository->create($request->all());
            return json('1001','创建成功',$notification);
        }catch (ValidatorException $e)
        {
            return json('4001',$e->getMessageBag()->first());
        }
    }
}
