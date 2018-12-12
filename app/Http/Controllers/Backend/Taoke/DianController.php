<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\DianValidator;
use App\Http\Requests\Taoke\DianCreateRequest;
use App\Http\Requests\Taoke\DianUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\DianRepository;

/**
 * Class DianController.
 */
class DianController extends Controller
{
    /**
     * @var DianRepository
     */
    protected $repository;

    /**
     * @var DianValidator
     */
    protected $validator;

    /**
     * DianController constructor.
     *
     * @param DianRepository $repository
     * @param DianValidator $validator
     */
    public function __construct(DianRepository $repository, DianValidator $validator)
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
        $dian = $this->repository->with('category')->paginate(request('limit', 10));

        return json(1001, '获取成功', $dian);
    }

    /**
     * @param DianCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DianCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $dian = $this->repository->create($request->all());

            return json(1001, '添加成功', $dian);
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
        $dian = $this->repository->find($id);

        return json(1001, '获取成功', $dian);
    }

    /**
     * @param DianUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DianUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $dian = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $dian);
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
