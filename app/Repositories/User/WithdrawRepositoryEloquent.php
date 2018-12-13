<?php

namespace App\Repositories\User;

use EasyWeChat\Factory;
use App\Models\User\Withdraw;
use App\Tools\Taoke\Commission;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Criteria\RequestCriteria;
use App\Validators\User\WithdrawValidator;
use Overtrue\LaravelWeChat\Facade;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\User\WithdrawRepository;

/**
 * Class WithdrawRepositoryEloquent.
 */
class WithdrawRepositoryEloquent extends BaseRepository implements WithdrawRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'out_trade_no' => 'like',
        'user_id',
        'status',
        'created_at',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Withdraw::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return WithdrawValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria (app (RequestCriteria::class));
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function create(array $attributes)
    {
        $user = getUser ();

        //验证金额
        $money = $attributes['money'];
        if ($money > $user->credit1) {
            return json (4001, '可提现金额不足');
        }
        $withdraw = db ('user_withdraws')->orderBy ('id', 'desc')->where ([
            'user_id' => $user->id,
            'status' => 0,
        ])->first ();

        if ($withdraw) {
            return json (4001, '已有在审核中的提现申请');
        }
        $attributes['status'] = 0;
        $attributes['user_id'] = $user->id;
        unset($attributes['token']);
        try {
            db ('user_withdraws')->insert ($attributes);

            return json (1001, '申请提现成功，请等待审核');
        } catch (\Exception $e) {
            return json (5001, '提现申请失败');
        }
    }

    /**
     * 提现记录
     * @param null $param
     * @return float|\Illuminate\Database\Query\Builder|int|mixed
     */
    public function getWithdrawChart($param = null)
    {
        $type = $param == null ? request ('type', 1) : $param;
        $user = getUser ();
        $commission = new Commission();

        //待结算
        if ($type == 1) {

            //自推收益
            $commission1 = $commission->getOrdersOrCommissionByDate ($user->id, [1], 'commission_rate1', true);
            //下级收益
            $commission2 = $commission->getOrdersOrCommissionByDate ($user->id, [1], 'commission_rate2', true);
            //组长收益
            $groupCommission1 = $commission->getOrdersOrCommissionByDate ($user->id, [1], 'group_rate1', true);
            //补贴收益
            $groupCommission2 = $commission->getOrdersOrCommissionByDate ($user->id, [1], 'group_rate2', true);

            return $commission1 + $commission2 + $groupCommission1 + $groupCommission2;
        }
        //累计结算
        if ($type == 2) {
            return db ('user_credit_logs')->where ([
                'column' => 'credit1',
                'type' => 2,
            ])->sum ('credit');
        }
        // 累计提现
        if ($type == 3) {
                return db ('user_credit_logs')->where ([
                'column' => 'credit1',
                'type' => 1,
            ])->sum ('credit');
        }
        return 0;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @throws \Throwable
     */
    public function mark()
    {
        $id = request ('id');
        $status = request ('status');
        //验证字段
        $validator = \Validator::make (request ()->all (), [
            'id' => 'required',
            'status' => [
                'required',
                Rule::in ([1, 0, 2]),
            ],
        ]);
        //字段验证失败
        if ($validator->fails ()) {
            throw  new \Exception($validator->errors ()->first ());
        }
        $withdraw = $this->model->newQuery ()->find ($id);

        if (!$withdraw) {
            return json (4001, '提现记录不存在');
        }
        //不在审核中
        if ($withdraw->status != 1) {
            return json (4001, '恶意提交');
        }
        // 拒绝提现
        if ($status == 0) {
            $this->update ([
                'status' => 2,
            ], $id);

            return json (1001, '拒绝提现成功');
        }
        //允许提现
        $user_member = $withdraw->user;
        //验证提现金额是否合法
        if ($user_member->credit1 < $withdraw->money) {
            return json (4001, '提现失败,提现金额大于余额');
        }

        $setting = setting (getUserId ());
        if (!$setting) {
            return json (4001, '没有进行系统设置');
        }
        $withdraw_set = data_get ($setting, 'withdraw');
        $withdraw_set = json_decode ($withdraw_set);
        $deduct_rate = data_get ($withdraw_set, 'deduct_rate');
        // 扣除金额
        $deduct_money = $withdraw->money * $deduct_rate / 100;

        //手动转账
        $pay_type = 3;
        //企业付款
        if ($status == 1) {
            if (!$user_member->wx_openid1) {
                return json (4001, '用户没有绑定微信');
            }

            $app = Facade::payment();

            $merchantPayData = [
                'partner_trade_no' => str_random (16),
                'openid' => $user_member->wx_openid1,
                'check_name' => 'NO_CHECK',
                're_user_name' => $user_member->nickname,
                'amount' => $withdraw->money * 100,
                'desc' => '淘宝返利提现',

            ];
            $rest = $app->transfer->toBalance($merchantPayData);

            if ($rest['result_code'] != 'SUCCESS' && $rest['return_code'] != 'SUCCESS') {
                throw new \Exception($rest['err_code_des']);
            }


            $pay_type = 1;
        }
        DB::transaction(function () use ($user_member,$deduct_money,$withdraw,$pay_type) {

            //修改订单状态
            $this->model->newQuery ()->where ('id', $withdraw->id)->update ([
                'status' => 3,
                'pay_type' => $pay_type,
                'real_money' => $withdraw->money - $deduct_money,
//                'deduct_money' => $deduct_money,
            ]);

            //减少余额
            $user_member->decrement ('credit1', $withdraw->money, [
                'type' => 12,
                'remark' => '淘宝返利提现'
            ]);

        });
        return json (1001, '提现成功');
    }
}
