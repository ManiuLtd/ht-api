<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\CategoryValidator;
use App\Http\Requests\Taoke\CategoryCreateRequest;
use App\Http\Requests\Taoke\CategoryUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\CategoryRepository;

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
     * CategoriesController constructor.
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository
            ->with(['children'])
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $categories);
    }

    /**
     * @param CategoryCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $category = $this->repository->create($request->all());

            return json(1001, '添加成功', $category);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->repository->find($id);

        return json(1001, '获取成功', $category);
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $category = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $category);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $coupon = db('tbk_coupons')->where('cat', $id)->first();
            if ($coupon) {
                throw  new \Exception('禁止删除已经包含优惠券的分类');
            }
            $this->repository->delete($id);

            return json(1001, '删除成功');
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
