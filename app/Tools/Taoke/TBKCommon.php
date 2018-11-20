<?php

namespace App\Tools\Taoke;

trait TBKCommon
{
    /**
     *  获取当前用户 的 pids.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|null|object
     */
    public function getPids()
    {
        $user = getUser ();
        $setting = setting (1); //应该是根据user或者user_id

        // 获取系统默认pid

        $user_pid = db ('tbk_pids')->where ('user_id', $user->id)->first ();

        //自己
        if ($user_pid) {
            return $user_pid;
        }
        //邀请人
        if ($user->inviter_id != null) {
            $inviter_pid = db ('tbk_pids')->where ('user_id', $user->inviter_id)->first ();

            if ($inviter_pid) {
                return $inviter_pid;
            }
        }

        if ($user->group_id != null) {
            $group = db ('groups')->find ($user->group_id);
            $group_pid = db ('tbk_pids')->where ('user_id', $group->user_id)->first ();
            //小组
            if ($group_pid) {
                return $group_pid;
            }
            // 代理设置
            $agent_setting = db ('tbk_settings')->where ([
                'user_id' => $group->user_id
            ])->first ();
            if ($agent_setting) {
                return $agent_setting->pid;
            }
        }

        return $this->arrayToObject ($setting->pid);
    }

    /**
     * @param $e
     * @return object|void
     */
    private function arrayToObject($e)
    {

        if (gettype ($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype ($v) == 'array' || getType ($v) == 'object')
                $e[$k] = (object)$this->arrayToObject ($v);
        }
        return (object)$e;
    }

    /**
     * @param $price
     * @return float|int
     */
    public function getFinalCommission($price)
    {
        $id = getUserId ();
        $commission = new Commission();
        return $commission->getCommissionByUser ($id, $price, 'commission_rate1');
    }

    /**
     * 设置信息
     * @return mixed
     */
    public function getSettings()
    {
        //读取代理设置
        $user = getUser ();
        $setting = setting (1); //应该是根据user或者user_id

        if ($user->group_id) {
            $group = db ('groups')->find ($user->group_id);
            if ($group) {
                $agent_setting = db ('tbk_settings')->where ([
                    'user_id' => $group->user_id
                ])->first ();
                if ($agent_setting) {
                    return $agent_setting;
                }
            }
        }
        return $setting;
    }

    public function localSearch($q)
    {
        $limit = request ('limit', 10);
        $sort = request ('sort');
        $type = request ('type');
        $where = [];

        $orderBy = [
            'column' => 'created_at',
            'orderBy' => 'desc'
        ];
        //1.综合  2销量（高到低) 3.销量（低到高），4.价格(低到高)，5.价格（高到低），6.佣金比例（高到低） 7. 卷额(从高到低) 8.卷额(从低到高)
        switch ($sort) {
            case 2:
                $orderBy['column'] = 'volume';
                $orderBy['orderBy'] = 'desc';
                break;
            case 3:
                $orderBy['column'] = 'volume';
                $orderBy['orderBy'] = 'asc';
                break;
            case 4:
                $orderBy['column'] = 'final_price';
                $orderBy['orderBy'] = 'asc';
                break;
            case 5:
                $orderBy['column'] = 'final_price';
                $orderBy['orderBy'] = 'desc';
                break;
            case 6 :
                $orderBy['column'] = 'commission_rate';
                $orderBy['orderBy'] = 'desc';
                break;
            case 7 :
                $orderBy['column'] = 'coupon_price';
                $orderBy['orderBy'] = 'desc';
                break;
            case 8:
                $orderBy['column'] = 'coupon_price';
                $orderBy['orderBy'] = 'asc';
                break;
            case 1:
                break;
            default:
                break;
        }
        $query = db ('tbk_coupons')->where ('type', $type)
            ->orderBy ($orderBy['column'], $orderBy['orderBy']);
        if(is_numeric ($q)){
            $query = $query->where('item_id',$q);
        }else{
            $query = $query ->orWhere ('title', 'LIKE', "%{$q}%")
                ->orWhere ('introduce', 'LIKE', "%{$q}%");
        }
        return $query->paginate($limit);

    }
}
