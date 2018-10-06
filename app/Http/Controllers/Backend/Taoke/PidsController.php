<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\PidValidator;
use App\Http\Requests\Taoke\PidCreateRequest;
use App\Http\Requests\Taoke\PidUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\PidRepository;

/**
 * Class PidsController.
 */
class PidsController extends Controller
{
    /**
     * @var PidRepository
     */
    protected $repository;

    /**
     * @var PidValidator
     */
    protected $validator;

    /**
     * CategoriesController constructor.
     *
     * @param PidRepository $repository
     * @param PidValidator $validator
     */
    public function __construct(PidRepository $repository, PidValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 推广位列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $pids = $this->repository->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $pids);
    }

    /**
     * 添加推广位.
     * @param PidCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PidCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $pieces = preg_split('/\s+/', $request->pid);
            if(count($pieces)){
                foreach ($pieces as $v){
                    $this->repository->updateOrCreate([
                        'pid' => $v,
                    ],[
                        'user_id' => getUserId(),
                        'member_id' => $request->member_id,
                        'type' => $request->type,
                    ]);
                }
            }
            return json(1001, '创建成功');
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 推广位详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $pids = $this->repository->find($id);

        return json(1001, '详情获取成功', $pids);
    }

    /**
     * 编辑推广位.
     * @param PidUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PidUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $pids = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $pids);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除推广位.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
