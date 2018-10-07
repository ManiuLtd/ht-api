<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:55
 */

namespace App\Http\Controllers\Api\Cms;


use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Cms\ArticleRepository;

/**
 * Class ArticlesController
 * @package App\Http\Controllers\Api\Cms
 */
class ArticlesController extends  Controller
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
     *  文章列表 根据分类id调用
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $type = request('category_id');
            if($type){
                return $this->repository->type_list($type);
            }else{
                return $this->repository->type_list();
            }
        } catch (Exception $e) {
            return json(5001,$e->getMessage());
        }
    }
}