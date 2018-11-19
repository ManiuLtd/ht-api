<?php

namespace App\Tools\Taoke;

use App\Models\User\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * 计算佣金和订单
 * Class Commission.
 */
class Commission
{
    /**
     * @param int $userId
     * @param float $commission
     * @param string $type
     * @return float|int
     */
    public function getCommissionByUser(int $userId, float $commission, string $type)
    {
        //检查会员
        $userModel = $this->checkUser($userId);

        //判断type类型
        $this->checkType($type);

        //会员没绑定等级
        if ($userModel->level_id == null) {
            return 0;
        }

        //判断当前Type的返佣比例
        if ($userModel->level->$type < 0) {
            return 0;
        }

        return $commission * $userModel->level->$type / 100;
    }

    /**
     * @param int $userId
     * @param array $orderStatus
     * @param string $type
     * @param bool $isCalculateTotalCommission
     * @param string|null $dateType
     * @return float|\Illuminate\Database\Query\Builder|int|mixed
     */
    public function getOrdersOrCommissionByDate(int $userId, array $orderStatus, string $type, bool $isCalculateTotalCommission = false, string $dateType = null)
    {
        //检查会员
        $userModel = $this->checkUser($userId);

        //判断type类型
        $this->checkType($type);

        //订单
        $query = db('tbk_orders')->whereIn('status', $orderStatus);

        //根据用户返佣层级筛选
        $query = $this->getQueryByType($userId, $type, $query, $userModel);

        //根据日期筛选
        $query = $this->getQueryByDateType($query, $dateType);

        //是否只算出总佣金数
        if ($isCalculateTotalCommission) {
            return $this->getCommissionByUser($userId, $query->sum('commission_amount'), $type);
        }

        return $query;
    }

    /**
     * 根据返佣层级筛选.
     * @param int $userId
     * @param string $type
     * @param $query
     * @param $userModel
     * @return mixed
     */
    public function getQueryByType(int $userId, string $type, $query, $userModel)
    {
        switch ($type) {
            case 'commission_rate1':
                $query = $query->where('user_id', $userId);
                break;
            case 'commission_rate2':
                $query = $query->whereIn('user_id', function ($query) use ($userId) {
                    $query->select('id')
                        ->from('users')
                        ->where('inviter_id', $userId);
                });
                break;
            case 'group_rate1':
                $query = $query->where('group_id', $userModel->group_id);
                break;
            case 'group_rate2':
                $query = $query->where('group_id', $userModel->oldgroup_id);
                break;
            default:
                break;
        }

        return $query;
    }

    /**
     * 根据日期筛选.
     * @param $query
     * @param $dateType
     * @return mixed
     */
    public function getQueryByDateType($query, $dateType)
    {
        //根据日期筛选
        switch ($dateType) {
            case 'today':
                $query = $query->whereDate('created_at', now()->toDateString());
                break;
            case 'yestday':
                $query = $query->whereDate('created_at', now()->addDay(-1)->toDateString());
                break;
            case 'week':
                $query = $query->whereDate('created_at', now()->addDay(-7)->toDateString());
                break;
            case 'month':
                $query = $query->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
                break;
            case 'lastMonth':
                $year = now()->month == 1 ? now()->addMonth(-1)->year : now()->year;
                $query = $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', now()->addMonth(-1)->month);
                break;
            default:
                break;
        }

        return $query;
    }

    /**
     * 检查Type是否符合格式.
     * @param string $type
     */
    protected function checkType(string $type): void
    {
        if (! in_array($type, ['group_rate1', 'group_rate2', 'commission_rate1', 'commission_rate2'])) {
            throw new InvalidParameterException('Type参数错误，仅支持：group_rate1，group_rate2，commission_rate1，commission_rate2');
        }
    }

    /**
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    protected function checkUser(int $userId)
    {
        $userModel = User::with('level')->find($userId);
        if (! $userModel) {
            throw new ModelNotFoundException('会员不存在');
        }

        return $userModel;
    }
}
