<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\KuaiQiangRepository;

/**
 * Class CategoriesController.
 */
class KuaiQiangController extends Controller
{
    /**
     * @var KuaiqiangRepository
     */
    protected $repository;

    /**
     * CategoriesController constructor.
     *
     * @param KuaiqiangRepository $repository
     */
    public function __construct(KuaiqiangRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 抢购.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $kuaiqiang = $this->repository
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $kuaiqiang);
    }
}
