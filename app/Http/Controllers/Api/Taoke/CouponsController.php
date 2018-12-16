<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Criteria\CouponPriceCriteria;
use App\Tools\Taoke\TBKInterface;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\CouponRepository;

/**
 * Class CouponsController.
 */
class CouponsController extends Controller
{
    /**
     * @var CouponRepository
     */
    protected $repository;

    /**
     * @var TBKInterface
     */
    protected $tbk;

    /**
     * CouponsController constructor.
     * @param CouponRepository $repository
     * @param tbkInterface $tbk
     */
    public function __construct(CouponRepository $repository, TBKInterface $tbk)
    {
        $this->repository = $repository;
        $this->tbk = $tbk;
    }

    /**
     * 优惠卷列表.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = $this->repository
            ->pushCriteria(new CouponPriceCriteria())
            ->paginate(request('limit', '10'));

        return json('1001', '优惠卷列表', $coupons);
    }

    /**
     * 详情.
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail()
    {
//        try {
            $detail = $this->tbk->getDetail();

            return json(1001, '获取成功', $detail);
//        } catch (\Exception $e) {
//            return json(5001, $e->getMessage());
//        }
    }

}
