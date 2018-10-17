<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class TimePickerCriteria.
 *
 * @package namespace App\Criteria;
 */
class TimePickerCriteria implements CriteriaInterface
{
    /**
     * 根据时间筛选，需要传入查询的时间.
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        //根据日期筛选
        $dateType = request('dateType');
        switch ($dateType) {
            case 'today'://今天
                $model = $model->whereDate('created_at', now()->toDateString());
                break;
            case 'yestday'://昨天
                $model = $model->whereDate('created_at', now()->addDay(-1)->toDateString());
                break;
            case 'week'://近一周
                $model = $model->whereDate('created_at','>', now()->addDay(-7)->toDateString());
                break;
            case 'halfmonth'://近半月
                $model = $model->whereDate('created_at','>', now()->addDay(-15)->toDateString());
                break;
                case 'month'://本月
                $model = $model->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
                break;
            case 'lastMonth'://上月
                $year = now()->month == 1 ? now()->addMonth(-1)->year : now()->year;
                $model = $model->whereYear('created_at', $year)
                    ->whereMonth('created_at', now()->addMonth(-1)->month);
                break;
            default:
                break;
        }
        return $model;
    }
}
