<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Validators\System\NotificationValidator;
use App\Http\Requests\System\NotificationCreateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
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
        $notifications = $this->repository
            ->with(['user','member'])
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $notifications);
    }

    /**
     * 添加通知.
     * @param NotificationCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NotificationCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $notification = $this->repository->create($request->all());

            return json(1001, '创建成功', $notification);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除通知.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
