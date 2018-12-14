<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\DianOrderValidator;
use App\Http\Requests\Taoke\DianOrderCreateRequest;
use App\Http\Requests\Taoke\DianOrderUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\DianOrderRepository;

/**
 * Class DianOrderController.
 */
class DianOrderController extends Controller
{
    /**
     * @var DianOrderRepository
     */
    protected $repository;

    /**
     * @var DianOrderValidator
     */
    protected $validator;

    /**
     * DianOrderController constructor.
     *
     * @param DianOrderRepository $repository
     * @param DianOrderValidator $validator
     */
    public function __construct(DianOrderRepository $repository, DianOrderValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $dianOrder = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $dianOrder);
    }

    /**
     * @param DianOrderCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DianOrderCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $dianOrder = $this->repository->create($request->all());

            return json(1001, '添加成功', $dianOrder);
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
        $dianOrder = $this->repository->find($id);

        return json(1001, '获取成功', $dianOrder);
    }

    /**
     * @param DianOrderUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DianOrderUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $dianOrder = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $dianOrder);
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
