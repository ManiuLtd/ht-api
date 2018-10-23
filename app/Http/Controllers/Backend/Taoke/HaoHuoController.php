<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\HaoHuoValidator;
use App\Http\Requests\Taoke\HaoHuoCreateRequest;
use App\Http\Requests\Taoke\HaoHuoUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\HaoHuoRepository;

/**
 * Class HaohuoController.
 */
class HaoHuoController extends Controller
{
    /**
     * @var HaoHuoRepository
     */
    protected $repository;

    /**
     * @var HaoHuoValidator
     */
    protected $validator;

    /**
     * HaohuoController constructor.
     * @param HaoHuoRepository $repository
     * @param HaoHuoValidator $validator
     */
    public function __construct(HaoHuoRepository $repository, HaoHuoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $haohuo = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $haohuo);
    }

    /**
     * @param HaoHuoCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(HaoHuoCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $haohuo = $this->repository->create($request->all());

            return json(1001, '添加成功', $haohuo);
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
        $haohuo = $this->repository->find($id);

        return json(1001, '获取成功', $haohuo);
    }

    /**
     * @param HaoHuoUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(HaoHuoUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $haohuo = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $haohuo);
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
