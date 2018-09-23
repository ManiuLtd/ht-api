<?php

namespace App\Http\Controllers\Backend\Member;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\Member\MemberWithdrawValidator;
use App\Repositories\Interfaces\WithdrawRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Member\MemberWithdrawUpdateRequest;

/**
 * Class MemberWithdrawsController.
 */
class MemberWithdrawsController extends Controller
{
    /**
     * @var WithdrawRepository
     */
    protected $repository;

    /**
     * @var MemberWithdrawValidator
     */
    protected $validator;

    /**
     * MemberWithdrawsController constructor.
     *
     * @param WithdrawRepository $repository
     * @param MemberWithdrawValidator $validator
     */
    public function __construct(WithdrawRepository $repository, MemberWithdrawValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  提现列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $withdraws = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->with('member')
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $withdraws);
    }

    /**
     * 提现详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $withdraw = $this->repository->find($id);

        return json(1001, '详情获取成功', $withdraw);
    }

    /**
     * 编辑提现.
     * @param MemberWithdrawUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MemberWithdrawUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $insert = $request->all();

            //TODO 根据status处理提现结果
            $withdraw = $this->repository->update($insert, $id);

            return json(1001, '更新成功', $withdraw);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除提现.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
