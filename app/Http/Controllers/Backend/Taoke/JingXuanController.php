<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\JingxuanDpValidator;
use App\Http\Requests\Taoke\JingXuanCreateRequest;
use App\Http\Requests\Taoke\JingXuanUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\JingXuanRepository;

/**
 * Class JingXuanController.
 */
class JingXuanController extends Controller
{
    /**
     * @var JingXuanRepository
     */
    protected $repository;

    /**
     * @var JingxuanDpValidator
     */
    protected $validator;

    /**
     * JingXuanController constructor.
     *
     * @param JingXuanRepository $repository
     * @param JingxuanDpValidator $validator
     */
    public function __construct(JingXuanRepository $repository, JingxuanDpValidator $validator)
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
        $jingxuanDps = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $jingxuanDps);
    }

    /**
     * @param JingXuanCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(JingXuanCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $jingxuan = $this->repository->create($request->all());

            return json(1001, '创建成功', $jingxuan);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = $this->repository->find($id);

        return json(1001, '获取成功', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param JingXuanUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(JingXuanUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $jingxuan = $this->repository->update($request->all(), $id);

            return json(1001, '修改成功', $jingxuan);
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
