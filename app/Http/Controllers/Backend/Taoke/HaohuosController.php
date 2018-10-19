<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\HaohuoValidator;
use App\Http\Requests\Taoke\HaohuoCreateRequest;
use App\Http\Requests\Taoke\HaohuoUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\HaohuoRepository;

/**
 * Class HaohuosController
 * @package App\Http\Controllers\Backend\Taoke
 */
class HaohuosController extends Controller
{
    /**
     * @var HaohuoRepository
     */
    protected $repository;

    /**
     * @var HaohuoValidator
     */
    protected $validator;

    /**
     * HaohuosController constructor.
     * @param HaohuoRepository $repository
     * @param HaohuoValidator $validator
     */
    public function __construct(HaohuoRepository $repository, HaohuoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $haohuos = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $haohuos);
    }

    /**
     * @param HaohuoCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(HaohuoCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $haohuo = $this->repository->create($request->all());

            return json(1001, '添加成功', $haohuo);
        } catch (ValidatorException $e) {
            return json(4001, $e->getMessageBag()->first());
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
     * @param HaohuoUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(HaohuoUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $haohuo = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $haohuo);
        } catch (ValidatorException $e) {
            return json(4001, $e->getMessageBag()->first());
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
