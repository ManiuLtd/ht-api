<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taoke\DianCreateRequest;
use App\Repositories\Interfaces\Taoke\DianRepository;
use App\Repositories\Interfaces\Taoke\GuessRepository;
use App\Tools\Taoke\Commission;
use App\Validators\Taoke\DianValidator;
use Illuminate\Http\Request;

/**
 * Class GuessController.
 */
class DianController extends Controller
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
    public function __construct(DianRepository $repository, DianValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;

    }

    /**
     * 小店列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $dian = $this->repository->paginate(request('limit', 10));
        return json(1001, '列表获取成功', $dian);
    }
    /**
     * 小店详情
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $dian = $this->repository->find($id);
        return json(1001, '详情获取成功', $dian);
    }

    /**
     * 小店分类列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        $categories = db('dian_categories')->where('status',1)->orderBy('sort','desc')->get();
        return json(1001, '获取成功', $categories);
    }

}
