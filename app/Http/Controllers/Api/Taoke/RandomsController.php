<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\CouponRepository;

/**
 * Class CouponsController.
 */
class RandomsController extends Controller
{
    /**
     * @var CouponRepository
     */
    protected $repository;

    /**
     * RandomsController constructor.
     * @param CouponRepository $repository
     */
    public function __construct(CouponRepository $repository)
    {
        $this->repository = $repository;

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            return $this->repository->random();
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }


}
