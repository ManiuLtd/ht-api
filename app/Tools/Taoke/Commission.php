<?php

namespace App\Tools\Taoke;

use App\Models\Member\Member;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * 计算佣金和订单
 * Class Commission
 * @package App\Tools\Taoke
 */
class Commission
{

    /**
     * @param int $memberId
     * @param float $commission
     * @param string $type
     * @return float|int
     */
    public function getComminnsionByMember(int $memberId, float $commission, string $type)
    {
        //检查会员
        $memberModel = $this->checkMember ($memberId);

        //判断type类型
        $this->checkType ($type);

        //会员没绑定等级
        if ($memberModel->level_id == null) {
            return 0;
        }

        //判断当前Type的返佣比例
        if ($memberModel->level->$type < 0) {
            return 0;
        }

        return $commission * $memberModel->level->$type / 100;
    }


    /**
     * @param int $memberId
     * @param array $orderStatus
     * @param string $type
     * @param bool $isCalculateTotalCommission
     * @param string|null $dateType
     * @return float|\Illuminate\Database\Query\Builder|int|mixed
     */
    public function getOrdersOrCommissionByDate(int $memberId, array $orderStatus, string $type, bool $isCalculateTotalCommission = false, string $dateType = null)
    {
        //检查会员
        $memberModel = $this->checkMember ($memberId);

        //判断type类型
        $this->checkType ($type);

        //订单
        $query = db ('tbk_orders')->whereIn ('status', $orderStatus);

        //根据用户返佣层级筛选
        $query = $this->getQueryByType ($memberId, $type, $query, $memberModel);

        //根据日期筛选
        $query = $this->getQueryByDateType ($query, $dateType);

       //是否只算出总佣金数
        if ($isCalculateTotalCommission) {
            return $this->getComminnsionByMember ($memberId, $query->sum ('commission_amount'), $type);
        }

        return $query;
    }


    /**
     * 根据返佣层级筛选
     * @param int $memberId
     * @param string $type
     * @param $query
     * @param $memberModel
     * @return mixed
     */
    public function getQueryByType(int $memberId, string $type, $query, $memberModel)
    {
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
                $query = $query->where ('group_id', $memberModel->group_id);
                break;
            case 'group_rate2':
                $query = $query->where ('group_id', $memberModel->oldgroup_id);
                break;
            default:
                break;
        }
        return $query;
    }


    /**
     * 根据日期筛选
     * @param $query
     * @param $dateType
     * @return mixed
     */
    public function getQueryByDateType($query, $dateType)
    {
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
        return $query;
    }



    /**
     * 检查Type是否符合格式
     * @param string $type
     */
    protected function checkType(string $type): void
    {
        if (!in_array ($type, ['group_rate1', 'group_rate2', 'commission_rate1', 'commission_rate2'])) {
            throw new InvalidParameterException('Type参数错误，仅支持：group_rate1，group_rate2，commission_rate1，commission_rate2');
        }
    }

    /**
     * @param int $memberId
     * @return Member|Member[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    protected function checkMember(int $memberId)
    {
        $memberModel = Member::with ('level')->find ($memberId);
        if (!$memberModel) {
            throw new ModelNotFoundException('会员不存在');
        }
        return $memberModel;
    }


}