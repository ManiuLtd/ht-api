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
     * @var JingxuanDpValidator
     */
    protected $validator;

    /**
     * JingXuanController constructor.
     *
     * @param JingXuanRepository $repository
     * @param JingxuanDpValidator $validator
     */
    public function __construct(JingXuanRepository $repository, JingxuanDpValidator $validator)
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
        try {
            $jingxuanDps = $this->repository->TaoCommand();

            return json(1001, '获取成功', $jingxuanDps);
        }catch (\Exception $e){
            return json(5001, $e->getMessage());
        }

    }

}
