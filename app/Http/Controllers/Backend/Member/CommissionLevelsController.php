<?php

namespace App\Http\Controllers\Backend\Member;

use App\Http\Controllers\Controller;
use App\Validators\Member\CommissionLevelValidator;
use App\Http\Requests\Member\CommissionLevelUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Member\CommissionLevelRepository;

/**
 * Class CommissionLevelsController.
 */
class CommissionLevelsController extends Controller
{
    /**
     * @var CommissionLevelRepository
     */
    protected $repository;

    /**
     * @var CommissionLevelValidator
     */
    protected $validator;

    /**
     * CommissionLevelsController constructor.
     *
     * @param CommissionLevelRepository $repository
     * @param CommissionLevelValidator $validator
     */
    public function __construct(CommissionLevelRepository $repository, CommissionLevelValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  分销等级列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $levels = $this->repository->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $levels);
    }

    /**
     * 分销等级详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $level = $this->repository->find($id);

        return json(1001, '详情获取成功', $level);
    }

    /**
     * 编辑分销等级.
     * @param CommissionLevelUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CommissionLevelUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $level = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $level);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除分销等级.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
