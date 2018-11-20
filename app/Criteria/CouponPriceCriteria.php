<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CouponPriceCriteria.
 */
class CouponPriceCriteria implements CriteriaInterface
{
    /**
     * 根据时间筛选，需要传入开始时间和结束时间.
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $min = request ('min_price');
        $max = request ('max_price');

        $where = [];
        if ($min) {
            $where[] = ['final_price', '<=', $min];

        }
        if ($max) {
            $where[] = ['final_price', '>=', $max];

        }
        if ($min || $max) {
            return $model->where ($where);
        }

        return $model;
    }
}
