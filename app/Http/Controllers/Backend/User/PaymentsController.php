<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Validators\User\PaymentValidator;
use App\Repositories\Interfaces\User\PaymentRepository;

/**
 * Class PaymentsController.
 */
class PaymentsController extends Controller
{
    /**
     * @var PaymentRepository
     */
    protected $repository;

    /**
     * @var PaymentValidator
     */
    protected $validator;

    /**
     * PaymentsController constructor.
     *
     * @param PaymentRepository $repository
     * @param PaymentValidator $validator
     */
    public function __construct(PaymentRepository $repository, PaymentValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 付款记录.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userPayments = $this->repository->with (['user'])->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $userPayments);
    }
}
