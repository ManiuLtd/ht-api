<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\GoodsCreateRequest;
use App\Http\Requests\Shop\GoodsUpdateRequest;
use App\Repositories\Interfaces\Shop\GoodsRepository;
use App\Validators\Shop\GoodsValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class GoodsController.
 */
class GoodsController extends Controller
{
    /**
     * @var GoodsRepository
     */
    protected $repository;

    /**
     * @var GoodsValidator
     */
    protected $validator;

    /**
     * GoodsController constructor.
     *
     * @param GoodsRepository $repository
     * @param GoodsValidator  $validator
     */
    public function __construct(GoodsRepository $repository, GoodsValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 商品列表
     * return \Illuminate\Http\Response.
     */
    public function index()
    {
        $goods = $this->repository->all();

        return json(1001, '列表获取成功', $goods);
    }

    /**
     * 添加商品
     *
     * @param GoodsCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GoodsCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $goods = $this->repository->create($request->all());

            return json(1001, '添加成功', $goods);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 商品详情.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $goods = $this->repository->with('categories')->find($id);

        return json(1001, '详情获取成功', $goods);
    }

    /**
     * 编辑商品
     *
     * @param GoodsUpdateRequest $request
     * @param                        $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GoodsUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $goods = $this->repository->update($request->all(), $id);

            return json(1001, '编辑成功', $goods);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除商品
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
