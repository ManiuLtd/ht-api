<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taoke\DianCreateRequest;
use App\Repositories\Interfaces\Taoke\DianRepository;
use App\Repositories\Interfaces\Taoke\GuessRepository;
use App\Tools\Taoke\Commission;
use App\Validators\Taoke\DianValidator;
use Illuminate\Http\Request;

/**
 * Class GuessController.
 */
class GuessController extends Controller
{
    /**
     * @var FavouriteRepository
     */
    protected $repository;

    /**
     * @var FavouriteValidator
     */
    protected $validator;

    /**
     * FavouritesController constructor.
     *
     * @param FavouriteRepository $repository
     * @param FavouriteValidator $validator
     */
    public function __construct(DianRepository $repository, DianValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 小店列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $dian = $this->repository->paginate(request('limit', 10));
        return json(1001, '获取成功', $dian);
    }
    /**
     * 小店详情
     * @return \Illuminate\Http\JsonResponse
     */
    public function diandetail(DianCreateRequest $request)
    {
        $id = $request->id;
        $dian = $this->repository->find($id);
        return json(1001, '获取成功', $dian);
    }

    /**
     * 小店分类列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        $categories = db('dian_categories')->where('status',1)->orderBy('sort','desc')->get();
        return json(1001, '获取成功', $categories);
    }

    //确认付款
    public function pay(Request $request)
    {
        $re = $request->all();
        $dian = $this->repository->find($re['id']);
        $memberid = getUserId();
        //平台扣除
        if($dian){
            $merchant = $re['money'] * (1 - $dian['deduct_rate'] / 100);//返给商家的金额
            $personal = $merchant * $dian['commission_rate'] / 100; //返给个人佣金

            db('users')
                ->where('id',$dian['user_id'])
                ->increment('credit1',$merchant);//商家金额增加

            db('users')->where('id',$memberid)
                ->increment('credit1',$personal);//个人金额增加

            $comm = new Commission();
            $commission = $comm->getCommissionByUser($dian['inviter_id'],$re['money'] * ( $dian['deduct_rate'] - $dian['commission_rate']) / 100 ,'commission_rate1');
            db('users')
                ->where('id',$dian['inviter_id'])
                ->increment('credit1',$commission);//邀请人金额增加
            return json(1001,'付款成功');
        }else{
            return json(4001, '小店不存在');
        }




    }



}
