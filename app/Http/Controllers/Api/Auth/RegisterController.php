<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\User\RegisterRequest;
use App\Repositories\Interfaces\System\SmsRepository;
use App\Repositories\Interfaces\User\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    public function __construct(UserRepository $repository,SmsRepository $smsRepository)
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
        try{
            return $this->repository->register();
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }
}
