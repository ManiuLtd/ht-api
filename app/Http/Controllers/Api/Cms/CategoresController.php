<?php

namespace App\Http\Controllers\Api\Cms;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Cms\CategoriesRepository;

/**
 * Class CategoresController.
 */
class CategoresController extends Controller
{
    /**
     * @var CategoriesRepository
     */
    protected $repository;

    /**
     * CategoresController constructor.
     * @param CategoriesRepository $repository
     */
    public function __construct(CategoriesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *  分类列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $article = $this->repository
                ->paginate(request('limit', 10));

            return json(1001, '列表获取成功', $article);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
