<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\JingxuanDpValidator;
use App\Http\Requests\Taoke\JingXuanCreateRequest;
use App\Http\Requests\Taoke\JingXuanUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\JingXuanRepository;

/**
 * Class JingXuanController.
 */
class JingXuanController extends Controller
{
    /**
     * @var JingXuanRepository
     */
    protected $repository;


    /**
     * JingXuanController constructor.
     *
     * @param JingXuanRepository $repository
     */
    public function __construct(JingXuanRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $jingxuan = $this->repository->getList();

            return json(1001, '获取成功', $jingxuan);
        }catch (\Exception $e){
            return json(5001, $e->getMessage());
        }

    }

}
