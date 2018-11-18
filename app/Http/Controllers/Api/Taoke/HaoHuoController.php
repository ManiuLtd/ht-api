<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\HaoHuoRepository;

/**
 * Class HaoHuoController.
 */
class HaoHuoController extends Controller
{
    /**
     * @var HaoHuoRepository
     */
    protected $repository;

    /**
     * HaoHuoController constructor.
     * @param HaoHuoRepository $repository
     */
    public function __construct(HaoHuoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 好货.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $haohuo = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $haohuo);
    }
}
