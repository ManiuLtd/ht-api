<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18.
 */

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Tools\Taoke\TBKInterface;
use App\Validators\Taoke\CategoryValidator;
use App\Repositories\Interfaces\Taoke\CategoryRepository;

/**
 * Class CategoriesController.
 */
class CategoriesController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected $repository;

    /**
     * @var CategoryValidator
     */
    protected $validator;
    /**
     * @var
     */
    protected $TBK;

    /**
     * CategoriesController constructor.
     * @param CategoryRepository $repository
     * @param CategoryValidator $validator
     * @param TBKInterface $TBK
     */
    public function __construct(CategoryRepository $repository, CategoryValidator $validator,TBKInterface $TBK)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->TBK = $TBK;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository
            ->paginate(request('limit', 100));

        return json(1001, '获取成功', $categories);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function super_category()
    {
        try {
            $classify = $this->TBK->super_category();
            return json(1001, '获取成功', $classify);
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }
}
