<?php

namespace App\Tools\Taoke;

use App\Models\Taoke\Setting;
use App\Models\User\User;
use mysql_xdevapi\Exception;

trait TBKCommon
{
    /**
     * 获取当前用户 的 pids.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|object|void|null
     * @throws \Exception
     */
    public function getPids()
    {
        $user = getUser ();

        $setting = $this->getDefaultSetting(); //应该是根据user或者user_id

        // 获取系统默认pid

        $user_pid = db ('tbk_pids')->where ('user_id', $user->id)->first ();

        //自己
        if (isset($user_pid->taobao) && $user_pid->taobao != null) {
            return $user_pid;
        }
        //邀请人
        if ($user->inviter_id != null) {
            $inviter_pid = db ('tbk_pids')->where ('user_id', $user->inviter_id)->first ();

            if (isset($inviter_pid->taobao) && $inviter_pid->taobao != null) {
                return $inviter_pid;
            }
        }

        if ($user->group_id != null) {
            $group = db ('groups')->find ($user->group_id);
            $group_pid = db ('tbk_pids')->where ('user_id', $group->user_id)->first ();
            //小组
            if (isset($group_pid->taobao) && $group_pid->taobao != null) {
                return $group_pid;
            }
            // 代理设置
            $agent_setting = db ('tbk_settings')->where ([
                'user_id' => $group->user_id
            ])->first ();
            if ($agent_setting) {
                return json_decode($agent_setting->pid);
            }
        }

        return $this->arrayToObject ($setting->pid);
    }

    /**
     * 系统设置
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \Exception
     */
    protected function getDefaultSetting()
    {
        $user = User::query()->where('is_default',1)->first();
        if (!$user) {
            throw new \Exception('没有默认系统用户');
        }

        $setting = $user->tbkSetting;

        if (!$setting) {
            throw new \Exception('没有默认的系统设置');
        }
        return $setting;
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
     * @param null $group_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \Exception
     */
    public function getSettings($group_id = null)
    {
        //读取代理设置
        $user = getUser ();
        $setting = $this->getDefaultSetting(); //应该是根据user或者user_id

        if (!$group_id) {
            $group_id = $user->group_id;
        }

        if ($group_id) {
            $group = db ('groups')->find ($group_id);
            if ($group) {
                $agent_setting = Setting::query()->where ([
                    'user_id' => $group->user_id
                ])->first ();
                if ($agent_setting) {
                    return $agent_setting;
                }
            }
        }

        return $setting;
    }

    /**
     * @param $q
     * @return array
     */
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
            $query = $query->whereRaw("(title like '%{$q}%' or introduce like '%{$q}%')");
        }

        $data = $query->paginate($limit)->toArray();

        return [
            'data' => $data['data'],
            //分页信息只要这四个参数就够了
            'meta' => [
                'current_page' => (int)$data['current_page'],
                'last_page' => $data['last_page'],
                'per_page' => $limit,
                'total' => $data['total'],
            ],
        ];
    }

    /**
     * 字符串过滤
     * @param $str
     * @return string
     */
    public function sensitiveWordFilter($str)
    {

        $filter = trim(setting(1)->filter,'"');

        $word = explode('|',$filter);

        $badword1 = array_combine($word,array_fill(0,count($word),''));

        $string = strtr($str, $badword1);

        return $string;

    }
}
