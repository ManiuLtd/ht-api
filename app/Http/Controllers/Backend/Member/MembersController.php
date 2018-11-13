<?php

namespace App\Http\Controllers\Backend\User;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\User\UserValidator;
use App\Http\Requests\User\UserUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\User\UserRepository;

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
     *  会员列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->with(['level', 'group', 'oldGroup', 'inviter'])
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $users);
    }

    /**
     * 会员详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = $this->repository->with(['level', 'group', 'oldGroup', 'inviter'])->find($id);

        return json(1001, '详情获取成功', $user);
    }

    /**
     * 编辑会员.
     * @param UserUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $user = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $user);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除会员.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
