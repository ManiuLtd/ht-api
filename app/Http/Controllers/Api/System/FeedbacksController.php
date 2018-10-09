<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use App\Validators\System\FeedbackValidator;
use App\Repositories\Interfaces\System\FeedbackRepository;
use Illuminate\Http\Request;
use Mockery\Exception;
use Prettus\Validator\Contracts\ValidatorInterface;

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
     * 添加留言反馈
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function index(Request $request)
    {
        try {
            $this->validator->with ($request->all ())->passesOrFail (ValidatorInterface::RULE_CREATE);

            $feedback = $this->repository->create ($request->all ());
            return json ('1001', '留言反馈成功', $feedback);

        } catch (Exception $e) {
            return json ('5001', $e->getMessage ());
        }
    }
}
