<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\FavouriteValidator;
use App\Repositories\Interfaces\Taoke\FavouriteRepository;
use App\Criteria\MemberCriteria;
use App\Http\Requests\Taoke\FavouriteCreateRequest;
use App\Http\Requests\Taoke\FavouriteUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class FavouritesController.
 */
class FavouritesController extends Controller
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
     * FavouritesController constructor.
     *
     * @param FavouriteRepository $repository
     * @param FavouriteValidator $validator
     */
    public function __construct(FavouriteRepository $repository, FavouriteValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 收藏列表.
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
     * 添加收藏
     * @param FavouriteCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FavouriteCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $category = $this->repository->create($request->all());

            return json(1001, '添加成功', $category);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage);
        }
    }

    /**
     * 取消收藏
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '取消成功');
    }
}
