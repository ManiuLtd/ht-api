<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\Taoke\DianCategoriesRepository;

use App\Repositories\Interfaces\Taoke\GuessRepository;

use App\Validators\Taoke\DianCategoriesValidator;


/**
 * Class GuessController.
 */
class DianCategoryController extends Controller
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
    public function __construct(DianCategoriesRepository $repository, DianCategoriesValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 小店分类列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $dian = $this->repository
            ->orderBy('sort','desc')
            ->paginate(request('limit', 10));
        return json(1001, '列表获取成功', $dian);
    }

}
