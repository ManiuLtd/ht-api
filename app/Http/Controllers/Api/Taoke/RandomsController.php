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
            $data = $this->repository->random();

            return json(1001, '获取成功', $data);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
