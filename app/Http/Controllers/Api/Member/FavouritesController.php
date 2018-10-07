<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Validators\Member\FavouriteValidator;
use App\Repositories\Interfaces\Member\FavouriteRepository;

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
     * //TODO 这是复制后端的  改一下
     * 添加收藏.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $memberFavourites = $this->repository
            ->with('goods')
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $memberFavourites);
    }
}
