<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\EntranceCategoryValidator;
use App\Http\Requests\Taoke\EntranceCategoryCreateRequest;
use App\Http\Requests\Taoke\EntranceCategoryUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\EntranceCategoryRepository;

/**
 * Class EntrancesController.
 */
class EntranceCategoriesController extends Controller
{
    /**
     * @var EntranceCategoryRepository
     */
    protected $repository;

    /**
     * @var EntranceCategoryValidator
     */
    protected $validator;

    /**
     * EntranceCategoriesController constructor.
     * @param EntranceCategoryRepository $repository
     * @param EntranceCategoryValidator $validator
     */
    public function __construct(EntranceCategoryRepository $repository, EntranceCategoryValidator $validator)
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
        $categories = $this->repository->with(['entrance','child'])->all();

        return json(1001, '获取成功', $categories);
    }


}
