<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Validators\System\FeedbackValidator;
use App\Http\Requests\System\FeedbackCreateRequest;
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
     * 留言列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $feedbacks = $this->repository
            ->with(['user','member'])
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $feedbacks);
    }
}
