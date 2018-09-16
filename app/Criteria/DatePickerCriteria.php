<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class DatePickerCriteria.
 *
 * @package namespace App\Criteria;
 */
class DatePickerCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $start = request ('start');
        $end = request ('end');
        if($start && $end){
            return $model->where([
                ['created_at','>=',$start],
                ['created_at','<=',$end],
            ]);
        }
        return $model;

    }
}
