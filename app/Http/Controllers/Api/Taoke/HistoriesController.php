<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Criteria\UserCriteria;
use App\Http\Controllers\Controller;
use App\Validators\Taoke\HistoryValidator;
use App\Http\Requests\Taoke\HistoryCreateRequest;
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
     * @throws \Prettus\Repository\Contracts\ValidatorException
     */
    public function store(HistoryCreateRequest $request)
    {
        try {
            $data = $request->all();
            $user = getUser();
            $data['user_id'] = $user->id;

            $histories = $this->repository->updateOrCreate([
                'item_id' => $data['item_id'],
                'user_id' => $data['user_id'],
            ], $data);

            return json(1001, '添加成功', $histories);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 取消足迹.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
