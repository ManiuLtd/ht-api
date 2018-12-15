<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\Taoke\DiyAdsRepository;

/**
 * Class GuessController.
 */
class DiyAdsController extends Controller
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
    public function __construct(DiyAdsRepository $repository)
    {
        $this->repository = $repository;

    }

    /**
     * 自定义广告列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $diyAds = $this->repository->paginate(request('limit', 10));
        return json(1001, '列表获取成功', $diyAds);
    }

}
