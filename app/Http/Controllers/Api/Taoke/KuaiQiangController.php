<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18.
 */

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\KuaiQiangValidator;
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
     * @var KuaiqiangValidator
     */
    protected $validator;

    /**
     * CategoriesController constructor.
     *
     * @param KuaiqiangRepository $repository
     * @param KuaiqiangValidator $validator
     */
    public function __construct(KuaiqiangRepository $repository, KuaiqiangValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 抢购
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $kuaiqiang = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $kuaiqiang);
    }
}
