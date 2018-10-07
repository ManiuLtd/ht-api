<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use App\Validators\System\FeedbackValidator;
use App\Repositories\Interfaces\System\FeedbackRepository;

/**
 * Class FeedbacksController.
 */
class FeedbacksController extends Controller
{
    /**
     * @var FeedbackRepository
     */
    protected $repository;

    /**
     * @var FeedbackValidator
     */
    protected $validator;

    /**
     * FeedbacksController constructor.
     *
     * @param FeedbackRepository $repository
     * @param FeedbackValidator $validator
     */
    public function __construct(FeedbackRepository $repository, FeedbackValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * //TODO 添加留言反馈
     */
    public function index()
    {

    }
}
