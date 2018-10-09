<?php
/**
 * Created by PhpStorm.
 * User: hongtang
 * Date: 2018/10/8
 * Time: 14:08
 */

namespace App\Tools\Taoke;

use App\Models\Member\Member;

/**
 * TODO NEED REVIEW
 * Class Commission
 * @package App\Tools\Taoke
 */
class Commission
{

    /**
     * 自推返佣
     * @param $id
     * @param string $date_type
     * @param int $type
     * @return array|mixed
     */
    public function selfPush($id, $date_type='month',$type=null)
    {
        $query = db('tbk_orders')->where([
            'member_id'=>$id,
            'status' => 3
        ])->whereYear('created_at', now()->year);

        //全部待结算
        if ($type == 1) {
            $cash = $query->sum('commission_amount');
            return $cash * $this->level($id)->commission_rate1;
        }

        $query = $this->getQuery($query,$date_type);
        $amount = $query->sum('commission_amount');
        $orderNum = $query->count();
        // 自买订单返佣-- （如果下级没有权限拿返佣，订单自动归属上级，上级如果也没权限，订单会系统）
        $money = $amount * $this->level($id)->commission_rate1;

        return [
            'money' => $money,
            'orderNum' => $orderNum,
        ];
    }

    /**
     * 下级订单我的提成
     * @param $id
     * @param string $date_type
     * @param null $type
     * @return float|int
     */
    public function subordinate($id, $date_type='month',$type=null)
    {
        $query = db('tbk_orders')->where('status', 3)
            ->whereIn('member_id',function ($query) use ($id) {
                $query->select('id')
                    ->from('members')
                    ->where([
                        'inviter_id'=> $id,
                        'isagent' => 0,
                    ]);
        })->whereYear('created_at', now()->year);
        //全部待结算
        if ($type == 1) {
            $cash = $query->sum('commission_amount');
            return $cash * $this->level($id)->commission_rate2;
        }

        $query = $this->getQuery($query, $date_type);

        $amount = $query->sum('commission_amount');


        $money = $amount * $this->level($id)->commission_rate2;

        return $money;


    }

    /**
     * 组长返佣
     * @param $id
     * @param string $date_type
     * @param null $type
     * @return float|int|mixed
     */
    public function leader($id, $date_type='month',$type=null)
    {
        $group = Member::find($id)->group;
        //我不是组长
        if ($id != $group->member_id) {
            return 0;
        }
        $query = db('tbk_orders')->where('status', 3)
            ->whereYear('created_at', now()->year)
            ->whereIn('member_id',function ($query) use ($group){
                $query->select('id')
                    ->from('members')
                    ->where('group_id', $group->id);
            });
        //全部待结算
        if ($type == 1) {
            $cash = $query->sum('commission_amount');
            return $cash * $this->level($id)->group_rate1;
        }

        $query = $this->getQuery($query, $date_type);
        $commission_amount = $query->sum('commission_amount');

        $money = $commission_amount * $this->level($id)->group_rate1;
        return $money;
    }

    /**
     * 老组长拿的新组长团队的佣金
     * @param $id
     * @param string $date_type
     * @param null $type
     * @return float|int|mixed
     */
    public function old_leader($id, $date_type='month',$type=null)
    {
        //我的团队
        $group = Member::find($id)->group;
        //不是其他用户的老组长
        if (!count($old_group = db('members')->select(['group_id'])->where('oldgroup_id', $group->id)->get())) {
            return 0;
        }
        $group_arr = $old_group->pluck('group_id')->toArray();
        $group_arr = array_unique($group_arr);

        $query = db('tbk_orders')->where('status', 3)
            ->whereYear('created_at', now()->year)
            ->whereIn('member_id',function ($query) use ($group_arr){
                $query->select('id')
                    ->from('members')
                    ->whereIn('group_id', $group_arr);
            });

        //全部待结算
        if ($type == 1) {
            $cash = $query->sum('commission_amount');
            return $cash * $this->level($id)->group_rate2;
        }
        $query = $this->getQuery($query, $date_type);
        $commission_amount = $query->sum('commission_amount');

        $money = $commission_amount * $this->level($id)->group_rate2;
        return $money;
    }

    /**
     * @param $query
     * @param $date_type
     * @return mixed
     */
    public function getQuery($query,$date_type)
    {
        switch ($date_type) {
            case 'month':
                return $query->whereMonth('created_at',now()->month);
                break;
            case 'lastMonth':
                return $query->whereMonth('created_at',now()->subMonth(1)->month);
                break;
            case 'day':
                return $query->whereMonth('created_at',now()->month)->whereDay('created_at', now()->day);
                break;
            case 'yesterday':
                return $query->whereMonth('created_at',now()->month)->whereDay('created_at', now()->subDay(1)->day);
                break;
            default:
                return $query->whereMonth('created_at',now()->month);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function level($id)
    {
        return Member::find($id)->commissionLevel;
    }

}