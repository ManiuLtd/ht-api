<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;


use App\Http\Controllers\Controller;
use App\Validators\Taoke\CategoryValidator; 
use App\Criteria\TypeCriteria;
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
     * CategoriesController constructor.
     *
     * @param CategoryRepository $repository
     * @param CategoryValidator $validator
     */
    public function __construct(CategoryRepository $repository, CategoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository
            ->pushCriteria (new TypeCriteria())
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $categories);
    }}