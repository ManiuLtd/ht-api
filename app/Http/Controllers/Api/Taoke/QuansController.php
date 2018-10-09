<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;


use App\Http\Controllers\Controller;
use App\Validators\Taoke\QuanValidator;
use App\Repositories\Interfaces\Taoke\QuanRepository;

/**
 * Class QuansController.
 */
class QuansController extends Controller
{
    /**
     * @var QuanRepository
     */
    protected $repository;

    /**
     * @var QuanValidator
     */
    protected $validator;

    /**
     * CategoriesController constructor.
     *
     * @param QuanRepository $repository
     * @param QuanValidator $validator
     */
    public function __construct(QuanRepository $repository, QuanValidator $validator)
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
        $quans = $this->repository
            ->with(['goods'])
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $quans);
    }

}