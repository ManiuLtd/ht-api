<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class DatePickerCriteria.
 */
class DatePickerCriteria implements CriteriaInterface
{
    /**
     * 根据时间筛选，需要传入开始时间和结束时间.
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $start = request('start');
        $end = request('end');

        if ($start && $end) {
            return $model->where([
                ['created_at', '>=', $start],
                ['created_at', '<=', $end],
            ]);
        }



        return $model;
    }
}
