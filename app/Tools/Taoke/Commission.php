<?php


namespace App\Tools\Taoke;

use App\Models\Member\Member;
use function GuzzleHttp\Promise\queue;

/**
 * TODO NEED REVIEW
 * Class Commission
 * @package App\Tools\Taoke
 */
class Commission
{


    /**
     * @param $memberId
     * @param $commission
     * @param $type
     * @return float|int
     */
    public function getComminnsionByMember($memberId, $commission, $type)
    {
        $memberModel = Member::with ('commissionLevel')->find ($memberId);

        //用户不存在
        if (!$memberModel) {
            return 0;
        }

        //判断type类型
        if (!in_array ($type, ['group_rate1', 'group_rate2', 'commission_rate1', 'commission_rate2'])) {
            return 0;
        }

        //没有分销等级
        if ($memberModel->level2 == null) {
            return 0;
        }
        //用户不是组长 不能结算组长返佣
        if ($memberModel->commissionLevel->type != 2 && ($type == 'group_rate1') || $type == 'group_rate2') {
            return 0;
        }

        return $commission * $memberModel->commissionLevel->$type / 100;
    }


    /**
     * @param $memberId
     * @param $orderStatus
     * @param $type
     * @param bool $isCalculateTotalCommission
     * @param null $dateType
     * @return float|int
     */
    public function getOrdersOrCommissionByDate($memberId, $orderStatus, $type, $isCalculateTotalCommission = false, $dateType = null)
    {
        $memberModel = Member::with ('commissionLevel')->find ($memberId);

        //用户不存在
        if (!$memberModel) {
            return 0;
        }
        //订单
        $query = db ('tbk_orders')->where ('status', $orderStatus);

        //根据用户返佣层级筛选
        switch ($type) {
            case 'commission_rate1':
                $query = $query->where ('member_id', $memberId);
                break;
            case 'commission_rate2':
                $query = $query->whereIn ('member_id', function ($query) use ($memberId) {
                    $query->select ('id')
                        ->from ('members')
                        ->where ('inviter_id', $memberId);
                });
                break;
            case 'group_rate1':
                //用户不是组长 不能结算组长返佣
                if ($memberModel->commissionLevel->type != 2 && ($type == 'group_rate1') || $type == 'group_rate2') {
                    break;
                }
                $query = $query->where ('group_id', $memberModel->group_id);
                break;
            case 'group_rate2':
                //用户不是组长 不能结算组长返佣
                if ($memberModel->commissionLevel->type != 2 && ($type == 'group_rate1') || $type == 'group_rate2') {
                    break;
                }
                $query = $query->where ('group_id', $memberModel->oldgroup_id);
                break;
            default:
                break;
        }


        //根据日期筛选
        switch ($dateType) {
            case 'today':
                $query = $query->whereDate ('created_at', now ()->toDateString ());
                break;
            case 'yestday':
                $query = $query->whereDate ('created_at', now ()->addDay (-1)->toDateString ());
                break;
            case 'week':
                $query = $query->whereDate ('created_at', now ()->addDay (-7)->toDateString ());
                break;
            case 'month':
                $query = $query->whereYear ('created_at', now ()->year)
                    ->whereMonth ('created_at', now ()->month);
                break;
            case 'lastMonth':
                $year = now ()->month == 1 ? now ()->addMonth (-1)->year : now ()->year;
                $query = $query->whereYear ('created_at', $year)
                    ->whereMonth ('created_at', now ()->addMonth (-1)->month);
                break;
            default:
                break;
        }
        if($isCalculateTotalCommission){
            return $this->getComminnsionByMember ($memberModel, $query->sum ('commission_amount'), $type);
        }

        return $query;
    }


}