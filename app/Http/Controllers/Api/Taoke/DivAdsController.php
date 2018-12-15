<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taoke\DianCreateRequest;
use App\Repositories\Interfaces\Taoke\DianRepository;
use App\Validators\Taoke\DianValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class GuessController.
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
     * @param DianRepository $repository
     * @param DianValidator $validator
     */
    public function __construct(DianRepository $repository, DianValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;

    }

    /**
     * 小店列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $dian = $this->repository->paginate(request('limit', 10));
        return json(1001, '列表获取成功', $dian);
    }

    /**
     * 申请成为店家
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
     * 小店详情
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $dian = $this->repository->find($id);
        return json(1001, '详情获取成功', $dian);
    }


}
