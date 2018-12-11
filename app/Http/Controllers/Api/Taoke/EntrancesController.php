<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\EntranceValidator;
use App\Http\Requests\Taoke\EntranceCreateRequest;
use App\Http\Requests\Taoke\EntranceUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\EntranceRepository;

/**
 * Class EntrancesController.
 */
class EntrancesController extends Controller
{
    /**
     * @var EntranceRepository
     */
    protected $repository;

    /**
     * @var EntranceValidator
     */
    protected $validator;

    /**
     * EntrancesController constructor.
     * @param EntranceRepository $repository
     * @param EntranceValidator $validator
     */
    public function __construct(EntranceRepository $repository, EntranceValidator $validator)
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
        $entrance = $this->repository->all();

        return json(1001, '获取成功', $entrance);
    }

}
