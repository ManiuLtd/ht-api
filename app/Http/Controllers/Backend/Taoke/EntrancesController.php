<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\EntranceValidator;
use App\Http\Requests\Taoke\EntranceCreateRequest;
use App\Http\Requests\Taoke\EntranceUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\EntranceRepository;

/**
 * Class EntrancesController.
 */
class EntrancesController extends Controller
{
    /**
     * @var EntranceRepository
     */
    protected $repository;

    /**
     * @var EntranceValidator
     */
    protected $validator;

    /**
     * EntrancesController constructor.
     * @param EntranceRepository $repository
     * @param EntranceValidator $validator
     */
    public function __construct(EntranceRepository $repository, EntranceValidator $validator)
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
        $categories = $this->repository->with(['category'])->paginate(request('limit', 10));

        return json(1001, '获取成功', $categories);
    }

    /**
     * @param EntranceCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EntranceCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $category = $this->repository->create($request->all());

            return json(1001, '添加成功', $category);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * @param EntranceUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EntranceUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $category = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $category);
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
