<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validators\Taoke\CouponValidator;
use App\Http\Requests\CouponCreateRequest;
use App\Http\Requests\CouponUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
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
     * CouponsController constructor.
     *
     * @param CouponRepository $repository
     * @param CouponValidator $validator
     */
    public function __construct(CouponRepository $repository, CouponValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *优惠卷列表,可根据type筛选 ,可根据价格、券额、佣金、销量排序
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = $this->repository
            ->with('goods')
            ->paginate(request('limit','10'));
        return json('1001','优惠卷列表',$coupons);
    }

    //分享
    public function share()
    {
        
    }
}