<?php

namespace App\Http\Controllers\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\CategoriesCreateRequest;
use App\Http\Requests\Cms\CategoriesUpdateRequest;
use App\Repositories\Interfaces\Cms\CategoriesRepository;
use App\Validators\Cms\CategoriesValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CategoriesController.
 */
class CategoriesController extends Controller
{
    /**
     * @var CategoriesRepository
     */
    protected $repository;

    /**
     * @var CategoriesValidator
     */
    protected $validator;

    /**
     * CategoriesController constructor.
     *
     * @param CategoriesRepository $repository
     * @param CategoriesValidator $validator
     */
    public function __construct(CategoriesRepository $repository, CategoriesValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 分类列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = $this->repository->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $categories);
    }

    /**
     * 添加分类.
     * @param CategoriesCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoriesCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $categories = $this->repository->create($request->all());

            return json(1001, '创建成功', $categories);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 编辑分类.
     * @param CategoriesUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoriesUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $categories = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $categories);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除分类.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
