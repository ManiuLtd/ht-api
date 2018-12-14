<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taoke\DianOrderCreateRequest;
use App\Repositories\Interfaces\Taoke\DianOrderRepository;
use App\Repositories\Interfaces\Taoke\GuessRepository;
use App\Tools\Taoke\Commission;
use App\Validators\Taoke\DianOrderValidator;
use Illuminate\Http\Request;

/**
 * Class GuessController.
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
     * @param DianOrderRepository $repository
     * @param DianOrderValidator $validator
     */
    public function __construct(DianOrderRepository $repository, DianOrderValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;

    }

    /**
     * 小店订单列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $dianOrder = $this->repository->paginate(request('limit', 10));
        return json(1001, '列表获取成功', $dianOrder);
    }
    /**
     * 小店订单详情
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $dianOrder = $this->repository->find($id);
        return json(1001, '详情获取成功', $dianOrder);
    }


}
