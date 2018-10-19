<?php


namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\HaoHuoValidator;
use App\Repositories\Interfaces\Taoke\HaoHuoRepository;

/**
 * Class HaoHuoController
 * @package App\Http\Controllers\Api\Taoke
 */
class HaoHuoController extends Controller
{
    /**
     * @var HaoHuoRepository
     */
    protected $repository;

    /**
     * @var HaoHuoValidator
     */
    protected $validator;

    /**
     * HaoHuoController constructor.
     * @param HaoHuoRepository $repository
     * @param HaoHuoValidator $validator
     */
    public function __construct(HaoHuoRepository $repository, HaoHuoValidator $validator)
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
        $categories = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $categories);
    }
}
