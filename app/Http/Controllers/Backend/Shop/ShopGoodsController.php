<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ShopGoodsCreateRequest;
use App\Http\Requests\Shop\ShopGoodsUpdateRequest;
use App\Repositories\Interfaces\ShopGoodsRepository;
use App\Validators\Shop\ShopGoodsValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ShopGoodsController.
 */
class ShopGoodsController extends Controller
{
    /**
     * @var ShopGoodsRepository
     */
    protected $repository;

    /**
     * @var ShopGoodsValidator
     */
    protected $validator;

    /**
     * ShopGoodsController constructor.
     *
     * @param ShopGoodsRepository $repository
     * @param ShopGoodsValidator  $validator
     */
    public function __construct(ShopGoodsRepository $repository, ShopGoodsValidator $validator)
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
     * @param ShopGoodsCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShopGoodsCreateRequest $request)
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
        $goods = $this->repository->find($id);

        return json(1001, '详情获取成功', $goods);
    }

    /**
     * 编辑商品
     *
     * @param ShopGoodsUpdateRequest $request
     * @param                        $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ShopGoodsUpdateRequest $request, $id)
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
