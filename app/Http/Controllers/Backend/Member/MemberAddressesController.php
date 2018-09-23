<?php

namespace App\Http\Controllers\Backend\Member;

use App\Http\Controllers\Controller;
use App\Validators\Member\MemberAddressValidator;
use App\Repositories\Interfaces\MemberAddressRepository;

/**
 * Class MemberAddressesController.
 */
class MemberAddressesController extends Controller
{
    /**
     * @var MemberAddressRepository
     */
    protected $repository;

    /**
     * @var MemberAddressValidator
     */
    protected $validator;

    /**
     * MemberAddressesController constructor.
     *
     * @param MemberAddressRepository $repository
     * @param MemberAddressValidator $validator
     */
    public function __construct(MemberAddressRepository $repository, MemberAddressValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 地址列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $memberAddresses = $this->repository->all();

        return json(1001, '列表获取成功', $memberAddresses);
    }
}
