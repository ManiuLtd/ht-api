<?php

namespace App\Http\Controllers\Api\Sms;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\System\SmsRepository;

class SmsController extends Controller
{
    /**
     * @var
     */
    protected $repository;

    /**
     * SmsController constructor.
     * @param SmsRepository $repository
     */
    public function __construct(SmsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            return $this->repository->sendSms();
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
