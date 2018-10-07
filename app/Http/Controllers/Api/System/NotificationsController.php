<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use App\Validators\System\NotificationValidator;
use Illuminate\Http\Request;
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
//       $member = auth()->user();
//        if(!$member){
//            return json('5001','该用户不存在');
//        }
        $data = $request->all();


    }


}
