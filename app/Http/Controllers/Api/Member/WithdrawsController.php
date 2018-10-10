<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Member;


use App\Http\Controllers\Controller;
use App\Http\Requests\Member\WithdrawCreateRequest;
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
    protected $repository;
    /**
     * @var
     */
    protected $validator;

    /**
     * WithdrawsController constructor.
     * @param WithdrawRepository $repository
     * @param WithdrawValidator $validator
     */
    public function __construct(WithdrawRepository $repository, WithdrawValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }


    /**
     * 获取提现信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $withdraw = $this->repository->getWithdrawData ();

        return json (1001, '获取成功', $withdraw);
    }


    /**
     * 发起提现
     * @param WithdrawCreateRequest $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store(WithdrawCreateRequest $request)
    {

        try {
            $this->validator->with (request ()->all ())->passesOrFail ();

            return $this->repository->withdraw ();

        } catch (\Exception $e) {
            return json (5001, $e->getMessage ());
        }
    }
}