<?php

namespace App\Http\Controllers\Backend\Member;

use App\Http\Controllers\Controller;
use App\Validators\Member\AddressValidator;
use App\Repositories\Interfaces\Member\AddressRepository;

/**
 * Class AddressesController.
 */
class AddressesController extends Controller
{
    /**
     * @var AddressRepository
     */
    protected $repository;

    /**
     * @var AddressValidator
     */
    protected $validator;

    /**
     * AddressesController constructor.
     *
     * @param AddressRepository $repository
     * @param AddressValidator $validator
     */
    public function __construct(AddressRepository $repository, AddressValidator $validator)
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
