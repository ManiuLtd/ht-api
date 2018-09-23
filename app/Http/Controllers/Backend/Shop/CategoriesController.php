<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CategoryCreateRequest;
use App\Http\Requests\Shop\CategoryUpdateRequest;
use App\Repositories\Interfaces\Shop\CategoryRepository;
use App\Validators\Shop\CategoryValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CategoriesController.
 */
class CategoriesController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected $repository;

    /**
     * @var CategoryValidator
     */
    protected $validator;

    /**
     * GoodsCategorysController constructor.
     *
     * @param CategoryRepository $repository
     * @param CategoryValidator $validator
     */
    public function __construct(CategoryRepository $repository, CategoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  商品分类列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = $this->repository->all();

        return json(1001, '列表获取成功', $categories);
    }

    /**
     * 添加商品分类.
     * @param CategoryCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $category = $this->repository->create($request->all());

            return json(1001, '创建成功', $category);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 编辑商品分类.
     * @param CategoryUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            // 验证分类
            if (! $this->checkCategory($id, true, $request->parentid)) {
                return json(4001, '分类编辑失败，请检查是否存在子分类');
            }

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $category = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $category);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除商品分类.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //验证分类
        if (! $this->checkCategory($id)) {
            return json(4001, '分类删除失败，请检查是否存在子分类');
        }

        $this->repository->delete($id);

        return json(1001, '删除成功');
    }

    /**
     * 验证分类.
     * @param $id
     * @param $isEdit
     * @param $parentId
     * @return bool
     */
    protected function checkCategory($id, $isEdit = false, $parentId = 0)
    {
        //验证是否有子分类
        $hasChild = db('shop_goods_categories')->where('parentid', $id)->first() ?? false;
        //禁止删除拥有子分类的分类
        if (! $isEdit && $hasChild) {
            return false;
        }
        //编辑时禁止设置拥有子分类的分类为二级分类
        if ($isEdit && $hasChild && $parentId != 0) {
            return false;
        }
        //验证分类是否存在
        $isExist = db('shop_goods_categories')->find($id) ?? false;

        if (! $isExist) {
            return false;
        }

        return true;
    }
}
