<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User\RegisterRequest;
use App\Repositories\Interfaces\User\UserRepository;
use App\Repositories\Interfaces\System\SmsRepository;

class RegisterController extends Controller
{
    /**
     * @var
     */
    protected $repository;
    /**
     * @var
     */
    protected $smsRepository;

    /**
     * RegisterController constructor.
     * @param UserRepository $repository
     * @param SmsRepository $smsRepository
     */
    public function __construct(UserRepository $repository, SmsRepository $smsRepository)
    {
        $this->repository = $repository;
        $this->smsRepository = $smsRepository;
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $this->repository->register();

            return json(1001, '注册成功', $data);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
