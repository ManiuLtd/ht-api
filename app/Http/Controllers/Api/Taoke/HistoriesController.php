<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\HistoryValidator;
use App\Repositories\Interfaces\Taoke\HistoryRepository;
use App\Criteria\MemberCriteria;
use App\Http\Requests\Taoke\HistoryCreateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class HistoriesController.
 */
class HistoriesController extends Controller
{
    /**
     * @var FavouriteRepository
     */
    protected $repository;

    /**
     * @var FavouriteValidator
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
        $memberFavourites = $this->repository
            ->pushCriteria (new MemberCriteria())
            ->paginate(request('limit', 10));
        return json(1001, '列表获取成功', $memberFavourites);
    }

    /**
     * 添加浏览记录
     * @param HistoryCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
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
}
