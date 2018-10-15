<?php

namespace App\Http\Controllers\Api\Image;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Image\BannerRepository;

/**
 * 图片管理
 * Class BannersController.
 */
class BannersController extends Controller
{
    /**
     * @var BannerRepository
     */
    protected $repository;

    /**
     * BannersController constructor.
     * @param BannerRepository $repository
     */
    public function __construct(BannerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $banners = $this->repository->paginate(request('limit', 10));

        return json('1001', '图片获取成功', $banners);
    }
}
