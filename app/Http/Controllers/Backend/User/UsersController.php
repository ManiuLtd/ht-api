<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\User\UserRepository;
use App\Validators\User\UserValidator;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * @apiDefine Success
 */
/**
 * Class UsersController.
 */
class UsersController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $repository
     * @param UserValidator $validator
     */
    public function __construct(UserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  用户列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userss = $this->repository->with (['group','level','oldGroup','inviter'])->paginate();

        return json(1001, '列表获取成功', $userss);
    }

    /**
     * 添加用户.
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $users = $this->repository->create($request->all());

            return json(1001, '创建成功', $users);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 用户详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $users = $this->repository->with (['group','level','oldGroup','inviter'])->find($id);

        return json(1001, '详情获取成功', $users);
    }

    /**
     * 编辑用户.
     * @param UserUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $users = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $users);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除用户.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
