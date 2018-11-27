<?php

namespace App\Repositories\Taoke;

use App\Models\User\User;
use App\Models\Taoke\Order;
use App\Tools\Taoke\Commission;
use App\Criteria\RequestCriteria;
use App\Validators\Taoke\OrderValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\OrderRepository;

/**
 * Class OrderRepositoryEloquent.
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'group_id',
        'oldgroup_id',
        'ordernum' => 'like',
        'title' => 'like',
        'itemid',
        'type',
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
        return Order::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return OrderValidator::class;
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
     * 后端可显示近一周、一月订单和佣金状态
     * 订单数据报表  可根据时间返回当前用户的佣金数或者订单数.
     * @param bool $isCommission 计算佣金或者订单数
     * @param array $params
     * @return float|\Illuminate\Database\Query\Builder|int|mixed
     */
    public function getOrderChart(bool $isCommission = true, array $params = [])
    {
        $user = getUser();
        $commission = new Commission();
        $dateType = $params['date_type'] ?? request('date_type', 'month');
        $status = $params['status'] ?? request('status', 1);

        //计算佣金
        if ($isCommission) {
            //自推佣金
            $commission1 = $commission->getOrdersOrCommissionByDate($user->id, [$status], 'commission_rate1', $isCommission, $dateType);
            //下级佣金
            $commission2 = $commission->getOrdersOrCommissionByDate($user->id, [$status], 'commission_rate2', $isCommission, $dateType);
            //组长佣金
            $groupCommission1 = $commission->getOrdersOrCommissionByDate($user->id, [$status], 'group_rate1', $isCommission, $dateType);
            //补贴佣金
            $groupCommission2 = $commission->getOrdersOrCommissionByDate($user->id, [$status], 'group_rate2', $isCommission, $dateType);

            return floatval(round($commission1 + $commission2 + $groupCommission1 + $groupCommission2, 2));
        }

        //计算订单数
        $group = $user->group;
        //如果用户是组长 直接返回小组订单数
        if ($group && $user->id == $group->user_id ?? null) {
            return $commission->getOrdersOrCommissionByDate($user->id, [1], 'group_rate1', $isCommission, $dateType)->count();
        } else {
            $commissionOrder1 = $commission->getOrdersOrCommissionByDate($user->id, [$status], 'commission_rate1', $isCommission, $dateType);
            $commissionOrder2 = $commission->getOrdersOrCommissionByDate($user->id, [$status], 'commission_rate2', $isCommission, $dateType);

            return $commissionOrder1->count() + $commissionOrder2->count();
        }
    }

    /**
     * 提交订单.
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitOrder()
    {
        $user = getUser();
        $ordernum = request('ordernum');
        if (is_numeric($ordernum) && strlen($ordernum) >= 16) {
            $re = db('tbk_user_orders')
                ->where('ordernum', $ordernum)
                ->first();
            if ($re) {
                return json(4001, '订单号已提交');
            }
            $order = db('tbk_orders')->where([
                'ordernum' => $ordernum,
            ])->first();
            if ($order) {
                db('tbk_user_orders')->insert([
                    'user_id' => $user->id,
                    'ordernum' => $ordernum,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ]);

                return json(1001, '订单提交成功');
            }
        }

        return json(4001, '订单格式不对');
    }

    /**
     * 获取会员收入信息.
     * @return array|mixed
     */
    public function getMember()
    {
        $lastMonth = $this->getOrderChart(true, ['date_type' => 'lastMonth','status' => 2]);//上月结算
        $month = $this->getOrderChart(true, ['date_type' => 'month','status' => 1]);//本月预估
        $day = $this->getOrderChart(true, ['date_type' => 'today','status' => 1]);//今日收益

        return [
            'lastMonth' => $lastMonth,
            'month' => $month,
            'today' => $day,
        ];
    }
}
