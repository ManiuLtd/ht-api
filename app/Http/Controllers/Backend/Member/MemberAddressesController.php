<?php

namespace App\Http\Controllers\Backend\Member;

use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\MemberAddressRepository;
use App\Validators\Member\MemberAddressValidator;

/**
 * Class MemberAddressesController
 *
 * @package App\Http\Controllers\Backend\Member
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
     * 地址列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $memberAddresses = $this->repository->all();

        return json(1001, '列表获取成功', $memberAddresses);
    }
}
