<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;


use App\Tools\Taoke\TBKInterface;
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
     * @var
     */
    protected $TBK;

    /**
     * CouponsController constructor.
     * @param CouponRepository $repository
     * @param CouponValidator $validator
     * @param TBKInterface $TBK
     */
    public function __construct(CouponRepository $repository, CouponValidator $validator,TBKInterface $TBK)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->TBK = $TBK;
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

    /**
     * 详情
     * @param $gid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($gid)
    {
        try {
            $this->validator->with(request()->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }catch (ValidatorException $exception) {
            return json(4001,$exception->getMessageBag()->first());
        }
        try {
            $detail = $this->TBK->getDetail(['id' => $gid]);
            if ($detail['code'] == 4004) {
                return json(4001, $detail['message']);
            }
            return json(1001, '获取成功', $detail['data']);
        }catch (\Exception $e) {
            return json(5001,$e->getMessage());
        }

    }

    //分享
    public function share()
    {
        
    }
}