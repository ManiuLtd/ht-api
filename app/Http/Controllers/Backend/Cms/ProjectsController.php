<?php

namespace App\Http\Controllers\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\ProjectsCreateRequest;
use App\Http\Requests\Cms\ProjectsUpdateRequest;
use App\Repositories\Interfaces\Cms\ProjectsRepository;
use App\Validators\Cms\ProjectsValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ProjectsController.
 */
class ProjectsController extends Controller
{
    /**
     * @var ProjectsRepository
     */
    protected $repository;

    /**
     * @var ProjectsValidator
     */
    protected $validator;

    /**
     * ProjectsController constructor.
     *
     * @param ProjectsRepository $repository
     * @param ProjectsValidator $validator
     */
    public function __construct(ProjectsRepository $repository, ProjectsValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 产品列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $projects = $this->repository->with(['categories','user'])->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $projects);
    }

    /**
     * 添加产品.
     * @param ProjectsCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProjectsCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $projects = $this->repository->create($request->all());

            return json(1001, '创建成功', $projects);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 编辑产品.
     * @param ProjectsUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProjectsUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $projects = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $projects);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除产品.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
