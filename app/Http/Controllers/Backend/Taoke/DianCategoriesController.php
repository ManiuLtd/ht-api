<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\DianCategoriesValidator;
use App\Http\Requests\Taoke\DianCategoriesCreateRequest;
use App\Http\Requests\Taoke\DianCategoriesUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\DianCategoriesRepository;

/**
 * Class DianCategoriesController.
 */
class DianCategoriesController extends Controller
{
    /**
     * @var DianCategoriesRepository
     */
    protected $repository;

    /**
     * @var DianCategoriesValidator
     */
    protected $validator;

    /**
     * DianCategoriesController constructor.
     *
     * @param DianCategoriesRepository $repository
     * @param DianCategoriesValidator $validator
     */
    public function __construct(DianCategoriesRepository $repository, DianCategoriesValidator $validator)
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
        $diancategories = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $diancategories);
    }

    /**
     * @param DianCategoriesCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DianCategoriesCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $diancategories = $this->repository->create($request->all());

            return json(1001, '添加成功', $diancategories);
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
        $diancategories = $this->repository->find($id);

        return json(1001, '获取成功', $diancategories);
    }

    /**
     * @param DianCategoriesUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DianCategoriesUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $diancategories = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $diancategories);
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
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
