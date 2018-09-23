<?php

namespace App\Http\Controllers\Backend\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\LevelCreateRequest;
use App\Http\Requests\Member\LevelUpdateRequest;
use App\Repositories\Interfaces\Member\LevelRepository;
use App\Validators\Member\LevelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class LevelsController.
 */
class LevelsController extends Controller
{
    /**
     * @var LevelRepository
     */
    protected $repository;

    /**
     * @var LevelValidator
     */
    protected $validator;

    /**
     * LevelsController constructor.
     *
     * @param LevelRepository $repository
     * @param LevelValidator $validator
     */
    public function __construct(LevelRepository $repository, LevelValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  等级列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $levels = $this->repository->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $levels);
    }

    /**
     * 添加等级.
     * @param LevelCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LevelCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $level = $this->repository->create($request->all());

            return json(1001, '创建成功', $level);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 等级详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $level = $this->repository->find($id);

        return json(1001, '详情获取成功', $level);
    }

    /**
     * 编辑等级.
     * @param LevelUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LevelUpdateRequest $request, $id)
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
     * 删除等级.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //检查该等级是否已有用户
        $member = db('members')
            ->where('level1', $id)
            ->first();
        if ($member) {
            return json(4001, '删除失败，该等级已有会员');
        }

        //删除等级
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
