<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\DianTagValidator;
use App\Http\Requests\Taoke\DianTagCreateRequest;
use App\Http\Requests\Taoke\DianTagUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\DianTagRepository;

/**
 * Class DianTagController.
 */
class DianTagController extends Controller
{
    /**
     * @var DianTagRepository
     */
    protected $repository;

    /**
     * @var DianTagValidator
     */
    protected $validator;

    /**
     * DianTagController constructor.
     *
     * @param DianTagRepository $repository
     * @param DianTagValidator $validator
     */
    public function __construct(DianTagRepository $repository, DianTagValidator $validator)
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
        $diantag = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $diantag);
    }

    /**
     * @param DianTagCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DianTagCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $diantag = $this->repository->create($request->all());

            return json(1001, '添加成功', $diantag);
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
        $diantag = $this->repository->find($id);

        return json(1001, '获取成功', $diantag);
    }

    /**
     * @param DianTagUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DianTagUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $diantag = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $diantag);
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
