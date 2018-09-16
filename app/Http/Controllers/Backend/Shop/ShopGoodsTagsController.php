<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Shop\ShopGoodsTagCreateRequest;
use App\Http\Requests\Shop\ShopGoodsTagUpdateRequest;
use App\Repositories\Interfaces\ShopGoodsTagRepository;
use App\Validators\Shop\ShopGoodsTagValidator;



/**
 * Class ShopGoodsTagsController.
 *
 * @package namespace App\Http\Controllers;
 */
class ShopGoodsTagsController extends Controller
{

    /**
     * @var ShopGoodsTagRepository
     */
    protected $repository;

    /**
     * @var ShopGoodsTagValidator
     */
    protected $validator;

    /**
     * ShopGoodsTagsController constructor.
     *
     * @param ShopGoodsTagRepository $repository
     * @param ShopGoodsTagValidator $validator
     */
    public function __construct(ShopGoodsTagRepository $repository, ShopGoodsTagValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  商品标签列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tags = $this->repository->paginate (request ('limit') ?? 10);

        return json (1001, '列表获取成功', $tags);
    }



    /**
     * 添加商品标签
     * @param ShopGoodsTagCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShopGoodsTagCreateRequest $request)
    {
        try {
            $this->validator->with ($request->all ())->passesOrFail (ValidatorInterface::RULE_CREATE);

            $tag = $this->repository->create ($request->all ());

            return json (1001, "创建成功", $tag);

        } catch (ValidatorException $e) {
            return json (5001, $e->getMessageBag()->first());
        }
    }

    /**
     * 商品标签详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $tag = $this->repository->find ($id);

        return json (1001, "详情获取成功", $tag);
    }



    /**
     * 编辑商品标签
     * @param ShopGoodsTagUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ShopGoodsTagUpdateRequest $request, $id)
    {

        try {
            $this->validator->with ($request->all ())->passesOrFail (ValidatorInterface::RULE_UPDATE);

            $tag = $this->repository->update ($request->all (), $id);

            return json (1001, "更新成功", $tag);

        } catch (ValidatorException $e) {
            return json (5001, $e->getMessageBag()->first());
        }
    }

    /**
     * 删除商品标签
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //删除商品标签
        $this->repository->delete ($id);

        return json (1001, "删除成功");
    }
}
