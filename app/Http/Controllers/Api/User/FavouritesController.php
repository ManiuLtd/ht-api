<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Validators\User\FavouriteValidator;
use App\Repositories\Interfaces\User\FavouriteRepository;

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
     * 添加收藏.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userFavourites = $this->repository
            ->with('goods')
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $userFavourites);
    }
}
