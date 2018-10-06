<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Validators\System\SmsValidator;
use App\Http\Requests\System\SmsCreateRequest;
use App\Repositories\Interfaces\System\SmsRepository;

/**
 * Class SmsController
 * @package App\Http\Controllers\Backend\System
 */
class SmsController extends Controller
{
    /**
     * @var SmsRepository
     */
    protected $repository;

    /**
     * @var SmsValidator
     */
    protected $validator;

    /**
     * SmsController constructor.
     * @param SmsRepository $repository
     * @param SmsValidator $validator
     */
    public function __construct(SmsRepository $repository, SmsValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 短信列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sms = $this->repository
            ->with('user')
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $sms);
    }
}
