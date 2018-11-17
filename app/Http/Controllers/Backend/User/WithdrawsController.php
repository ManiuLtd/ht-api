<?php

namespace App\Http\Controllers\Backend\User;

use App\Models\User\Withdraw;
use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\User\WithdrawValidator;
use App\Http\Requests\User\WithdrawUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\User\WithdrawRepository;

/**
 * Class WithdrawsController.
 */
class WithdrawsController extends Controller
{
    /**
     * @var WithdrawRepository
     */
    protected $repository;

    /**
     * @var WithdrawValidator
     */
    protected $validator;

    /**
     * WithdrawsController constructor.
     *
     * @param WithdrawRepository $repository
     * @param WithdrawValidator $validator
     */
    public function __construct(WithdrawRepository $repository, WithdrawValidator $validator)
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
            ->with('user')
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
     * @param WithdrawUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(WithdrawUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $insert = $request->all();

            //只有状态是审核中才能修改状态----
            $withdraw_status = Withdraw::query()->find($id);
            if ($withdraw_status->status == 1 || $withdraw_status->status == 2) {
                $insert['status'] = $withdraw_status->status;
            }

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

    public function mark()
    {
        return $this->repository->mark();
    }
}
