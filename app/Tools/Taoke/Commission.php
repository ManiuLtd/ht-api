<?php
/**
 * Created by PhpStorm.
 * User: hongtang
 * Date: 2018/10/8
 * Time: 14:08
 */

namespace App\Tools\Taoke;

use App\Models\Member\Member;
use App\Repositories\Interfaces\Member\CommissionLevelRepository;
use Illuminate\Support\Facades\DB;


class Commission
{
    /**
     * @var
     */
    protected $commissionLevelRepository;


    /**
     * Commission constructor.
     * @param CommissionLevelRepository $commissionLevelRepository
     */
    public function __construct(CommissionLevelRepository $commissionLevelRepository)
    {
        $this->commissionLevelRepository = $commissionLevelRepository;

    }

    /**
     * 自推返佣
     * @param $id
     * @param string $date_type
     * @return float|int
     */
    public function selfPush($id, $date_type='month')
    {
        $query = db('tbk_orders')->where([
            'member_id'=>$id,
            'status' => 3
        ])->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month);
        $commission_amount = 0;
        if ($date_type == 'month') {
            $commission_amount = $query->sum('commission_amount');
        }
        if ($date_type == 'day') {
            $commission_amount = $query->whereDay('created_at', now()->day)->sum('commission_amount');
        }


        // 自买订单返佣-- （如果下级没有权限拿返佣，订单自动归属上级，上级如果也没权限，订单会系统）
        $money2 = $commission_amount * $this->level($id)->commission_rate1;
        return $money2;
    }

    /**
     * 下级订单我的提成
     * @param $id
     * @param string $date_type
     * @return float|int
     */
    public function subordinate($id, $date_type='month')
    {
        $query = db('tbk_orders')->where('status', 3)

            ->whereIn('member_id',function ($query) use ($id) {
                $query->select('id')
                    ->from('members')
                    ->where([
                        'inviter_id'=> $id,
                        'isagent' => 0,
                    ]);
            })->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month);
        $commission_amount = 0;
        if ($date_type == 'month') {
            $commission_amount = $query->sum('commission_amount');
        }
        if ($date_type == 'day') {
            $commission_amount = $query->whereDay('created_at', now()->day)->sum('commission_amount');
        }


        $money = $commission_amount * $this->level($id)->commission_rate2;
        return $money;
    }

    /**
     * 组长返佣
     * @param $id
     * @param string $date_type
     * @return float|int
     */
    public function leader($id, $date_type='month')
    {
        $group = Member::find($id)->group;
        //我不是组长
        if ($id != $group->member_id) {
            return 0;
        }
        $query = db('tbk_orders')->where('status', 3)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at',now()->month)
            ->whereIn('member_id',function ($query) use ($group){
                $query->select('id')
                    ->from('members')
                    ->where('group_id', $group->id);
            });

        $commission_amount = 0;
        if ($date_type == 'month') {
            $commission_amount = $query->sum('commission_amount');
        }
        if ($date_type == 'day') {
            $commission_amount = $query->whereDay('created_at', now()->day)->sum('commission_amount');
        }
        $money = $commission_amount * $this->level($id)->group_rate1;
        return $money;
    }

    /**
     * 老组长拿的新组长团队的佣金
     * @param $id
     * @param string $date_type
     * @return float|int
     */
    public function old_leader($id, $date_type='month')
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
            ->whereMonth('created_at',now()->month)
            ->whereIn('member_id',function ($query) use ($group_arr){
                $query->select('id')
                    ->from('members')
                    ->whereIn('group_id', $group_arr);
            });
         $commission_amount = 0;
        if ($date_type == 'month') {
            $commission_amount = $query->sum('commission_amount');
        }
        if ($date_type == 'day') {
            $commission_amount = $query->whereDay('created_at', now()->day)->sum('commission_amount');
        }

        $money = $commission_amount * $this->level($id)->group_rate2;
        return $money;
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