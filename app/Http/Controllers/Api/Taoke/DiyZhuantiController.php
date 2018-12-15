<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\Taoke\DiyZhuantiRepository;

/**
 * Class GuessController.
 */
class DiyZhuantiController extends Controller
{
    /**
     * @var DiyAdsRepository
     */
    protected $repository;

    /**
     * DiyAdsController constructor.
     * @param DiyAdsRepository $repository
     *
     */
    public function __construct(DiyZhuantiRepository $repository)
    {
        $this->repository = $repository;

    }

    /**
     * 自定义专题列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $diyAds = $this->repository->paginate(request('limit', 10));
        return json(1001, '列表获取成功', $diyAds);
    }

}
