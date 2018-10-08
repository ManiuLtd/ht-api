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
use App\Validators\Member\WithdrawValidator;
use Prettus\Validator\Exceptions\ValidatorException;

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
     * @var
     */
    protected $withdrawValidator;

    /**
     * WithdrawsController constructor.
     * @param WithdrawRepository $withdrawRepository
     * @param WithdrawValidator $withdrawValidator
     */
    public function __construct(WithdrawRepository $withdrawRepository,WithdrawValidator $withdrawValidator)
    {
        $this->withdrawRepository = $withdrawRepository;
        $this->withdrawValidator = $withdrawValidator;
    }

    //提现功能  不能重复提现
    public function index()
    {
        try {
            $this->withdrawValidator->with(request()->all())->passesOrFail();
        }catch (ValidatorException $e){
            return json(4001,$e->getMessageBag()->first());
        }
        try {
            return $this->withdrawRepository->withdraw();
        } catch (\Exception $e) {
            return response()->json([
                'code' => 4001,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        //提现
        try {
            $withdraw = $this->withdrawRepository->getWithdrawData();
            return json(1001,'获取成功',$withdraw);
        }catch (\Exception $e){

            return json(4001,$e->getMessage());
        }

    }

}