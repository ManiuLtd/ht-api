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
     * //TODO 添加留言反馈
     */
    public function index(Request $request)
    {
//        $member = auth()->user();
//        if(!$member){
//            return json('5001','该用户不存在');
//        }
        try{
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $data = $request->all();
                //添加留言反馈
                $feedback = $this->repository->create([
                    'member_id'=>1,
                    'content'=>$data['content'],
                    'images'=>$data['images'],
                ]);
                return json('1001','留言反馈成功',$feedback);
        }catch (Exception $e)
        {
            return json('5001',$e->getMessage());
        }
    }
}
