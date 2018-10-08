<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Member;


use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Member\WithdrawRepository;

/**
 * 提现
 * Class WithdrawsController
 * @package App\Http\Controllers\Api\Member
 */
class WithdrawsController extends Controller
{
    /**
     * @var
     */
    protected $withdrawRepository;

    /**
     * WithdrawsController constructor.
     * @param WithdrawRepository $withdrawRepository
     */
    public function __construct(WithdrawRepository $withdrawRepository)
    {
        $this->withdrawRepository = $withdrawRepository;
    }

    //TODO 提现功能  不能重复提现
    public function index()
    {
//        try {
            return $this->withdrawRepository->recharge();
//        } catch (\Exception $e) {
//            return response()->json([
//                'code' => 5001,
//                'message' => $e->getMessage()
//            ]);
//        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        //提现
        try {
            return $this->withdrawRepository->getWithdrawData();
        }catch (\Exception $e){

            return json(4004,$e->getMessage());
        }

    }

}