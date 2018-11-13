<?php

namespace App\Repositories\User;

use App\Models\User\Withdraw;
use App\Tools\Taoke\Commission;
use App\Criteria\RequestCriteria;
use App\Validators\User\WithdrawValidator;
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
        $this->pushCriteria(app(RequestCriteria::class));
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
        $user = getUser();

        //验证金额
        $money = $attributes['money'];
        if ($money > $user->credit1) {
            return json(4001, '可提现金额不足');
        }
        $withdraw = db('user_withdraws')->orderBy('id', 'desc')->where([
            'user_id' => $user->id,
            'status' => 0,
        ])->first();

        if ($withdraw) {
            return json(4001, '已有在审核中的提现申请');
        }
        $attributes['status'] = 0;
        $attributes['user_id'] = $user->id;
        try {
            db('user_withdraws')->insert($attributes);

            return json(1001, '申请提现成功，请等待审核');
        } catch (\Exception $e) {
            return json(5001, '提现申请失败');
        }
    }

    /**
     * 提现记录.
     * @return array|mixed
     */
    public function getWithdrawChart()
    {
        $type = request('type', 1);
        $user = getUser();
        $commission = new Commission();

        //待结算
        if ($type == 1) {

            //自推收益
            $commission1 = $commission->getOrdersOrCommissionByDate($user->id, [1], 'commission_rate1', true);
            //下级收益
            $commission2 = $commission->getOrdersOrCommissionByDate($user->id, [1], 'commission_rate2', true);
            //组长收益
            $groupCommission1 = $commission->getOrdersOrCommissionByDate($user->id, [1], 'group_rate1', true);
            //补贴收益
            $groupCommission2 = $commission->getOrdersOrCommissionByDate($user->id, [1], 'group_rate2', true);

            return $commission1 + $commission2 + $groupCommission1 + $groupCommission2;
        }
        //累计结算
        if ($type == 2) {
            return db('user_credit_logs')->where([
                'column' => 'credit1',
                'type' => 11,
            ])->sum('credit');
        }
        // 累计提现
        if ($type == 3) {
            return db('user_credit_logs')->where([
                'column' => 'credit1',
                'type' => 12,
            ])->sum('credit');
        }
    }
}
