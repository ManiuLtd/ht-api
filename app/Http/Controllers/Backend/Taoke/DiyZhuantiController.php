<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\DiyZhuantiValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Http\Requests\Taoke\DiyZhuantiCreateRequest;
use App\Http\Requests\Taoke\DiyZhuantiUpdateRequest;
use App\Repositories\Interfaces\Taoke\DiyZhuantiRepository;

/**
 * Class DiyZhuantiController.
 */
class DiyZhuantiController extends Controller
{
    /**
     * @var DiyZhuantiRepository
     */
    protected $repository;

    /**
     * @var DiyZhuantiValidator
     */
    protected $validator;

    /**
     * DiyZhuantiController constructor.
     *
     * @param DiyZhuantiRepository $repository
     * @param DiyZhuantiValidator $validator
     */
    public function __construct(DiyZhuantiRepository $repository, DiyZhuantiValidator $validator)
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
        $diyZhuanti = $this->repository
            ->with(['children'])
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $diyZhuanti);
    }

    /**
     * @param DiyZhuantiCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DiyZhuantiCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $diyZhuanti = $this->repository->create($request->all());

            return json(1001, '添加成功', $diyZhuanti);
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
        $diyZhuanti = $this->repository->find($id);

        return json(1001, '获取成功', $diyZhuanti);
    }

    /**
     * @param DiyZhuantiUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DiyZhuantiUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $diyZhuanti = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $diyZhuanti);
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
