<?php

namespace App\Http\Controllers\Api\Cms;

use App\Http\Controllers\Controller;
use App\Criteria\ArticleCategoryCriteria;
use App\Repositories\Interfaces\Cms\ArticleRepository;

/**
 * Class ArticlesController.
 */
class ArticlesController extends Controller
{
    /**
     * @var ArticleRepository
     */
    protected $repository;

    /**
     * ArticlesController constructor.
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *  文章列表 根据分类id调用.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $article = $this->repository
                ->pushCriteria(new ArticleCategoryCriteria())
                ->with(['category'])
                ->paginate(request('limit', 10));

            return json(1001, '列表获取成功', $article);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $article = $this->repository->find($id);

        return json(1001, '获取成功', $article);
    }
}
