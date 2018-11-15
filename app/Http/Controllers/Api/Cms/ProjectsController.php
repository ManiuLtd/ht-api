<?php

namespace App\Http\Controllers\Api\Cms;

use App\Http\Controllers\Controller;
use App\Criteria\ArticleCategoryCriteria;
use App\Repositories\Interfaces\Cms\ProjectRepository;

/**
 * Class ProjectsController.
 */
class ProjectsController extends Controller
{
    /**
     * @var ProjectRepository
     */
    protected $repository;

    /**
     * ProjectsController constructor.
     * @param ProjectRepository $repository
     */
    public function __construct(ProjectRepository $repository)
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
}
