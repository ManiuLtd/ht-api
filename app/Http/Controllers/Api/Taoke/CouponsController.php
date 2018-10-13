<?php

namespace App\Http\Controllers\Api\Taoke;


use App\Tools\Taoke\TBKInterface;
use App\Http\Controllers\Controller;
use App\Validators\Taoke\CouponValidator;;
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
     * @var CouponValidator
     */
    protected $validator;
    /**
     * @var
     */
    protected $tbk;

    /**
     * CouponsController constructor.
     * @param CouponRepository $repository
     * @param CouponValidator $validator
     * @param tbkInterface $tbk
     */
    public function __construct(CouponRepository $repository, CouponValidator $validator,TBKInterface $tbk)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->tbk = $tbk;
    }

    /**
     * 优惠卷列表,可根据type筛选 ,可根据价格、券额、佣金、销量排序
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = $this->repository->paginate(request('limit','10'));

        return json('1001','优惠卷列表',$coupons);
    }

    /**
     * 详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $detail = $this->tbk->getDetail(['id' => $id]);
            if ($detail['code'] == 4004) {
                return json(4001, $detail['message']);
            }
            return json(1001, '获取成功', $detail['data']);
        }catch (\Exception $e) {
            return json(5001,$e->getMessage());
        }

    }
}