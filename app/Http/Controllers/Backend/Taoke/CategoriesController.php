<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taoke\CategoryCreateRequest;
use App\Http\Requests\Taoke\CategoryUpdateRequest;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\CategoryRepository;
use App\Validators\Taoke\CategoryValidator;

/**
 * Class CategoriesController.
 *
 * @package namespace App\Http\Controllers\Taoke;
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
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository->paginate(request('limit', 10));

        return json(1001,'获取成功',$categories);
    }

    /**
     * @param CategoryCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail();

            $category = $this->repository->create($request->all());
            return json(1001,'添加成功',$category);
        } catch (ValidatorException $e) {
            return json(4001,$e->getMessageBag()->first());
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

        return json(1001,'获取成功',$category);
    }


    /**
     * @param CategoryUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail();

            $category = $this->repository->update($request->all(), $id);

            return json(1001,'修改成功', $category);

        } catch (ValidatorException $e) {

            return json(4001,$e->getMessageBag()->first());
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

        $this->repository->delete($id);

        return json(1001,'删除成功');
    }
}
