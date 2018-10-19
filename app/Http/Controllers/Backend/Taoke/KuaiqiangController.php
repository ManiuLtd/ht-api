<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\KuaiqiangValidator;
use App\Http\Requests\Taoke\KuaiqiangCreateRequest;
use App\Http\Requests\Taoke\KuaiqiangUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\KuaiqiangRepository;

/**
 * Class KuaiqiangController.
 */
class KuaiqiangController extends Controller
{
    /**
     * @var KuaiqiangRepository
     */
    protected $repository;

    /**
     * @var KuaiqiangValidator
     */
    protected $validator;

    /**
     * KuaiqiangController constructor.
     *
     * @param KuaiqiangRepository $repository
     * @param KuaiqiangValidator $validator
     */
    public function __construct(KuaiqiangRepository $repository, KuaiqiangValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 优惠券列表.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $kuaiqiang = $this->repository->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $kuaiqiang);
    }

    /**
     * 添加优惠券.
     *
     * @param KuaiqiangCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(KuaiqiangCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $kuaiqiang = $this->repository->create($request->all());

            return json(1001, '创建成功', $kuaiqiang);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 优惠券详情.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $kuaiqiang = $this->repository->find($id);

        return json(1001, '详情获取成功', $kuaiqiang);
    }

    /**
     * 编辑优惠券.
     *
     * @param KuaiqiangUpdateRequest $request
     * @param                         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(KuaiqiangUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $kuaiqiang = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $kuaiqiang);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除优惠券.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
