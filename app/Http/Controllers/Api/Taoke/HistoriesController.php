<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Criteria\UserCriteria;
use App\Http\Controllers\Controller;
use App\Validators\Taoke\HistoryValidator;
use App\Http\Requests\Taoke\HistoryCreateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\HistoryRepository;

/**
 * Class HistoriesController.
 */
class HistoriesController extends Controller
{
    /**
     * @var HistoryRepository
     */
    protected $repository;

    /**
     * @var HistoryValidator
     */
    protected $validator;

    /**
     * HistoriesController constructor.
     *
     * @param HistoryRepository $repository
     * @param HistoryValidator $validator
     */
    public function __construct(HistoryRepository $repository, HistoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 浏览记录列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $histories = $this->repository
            ->pushCriteria(new UserCriteria())
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $histories);
    }

    /**
     * 添加浏览记录.
     * @param HistoryCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(HistoryCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $category = $this->repository->create($request->all());

            return json(1001, '添加成功', $category);
        } catch (ValidatorException $e) {
            return json(4001, $e->getMessageBag()->first());
        }
    }

    /**
     * 取消收藏.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
