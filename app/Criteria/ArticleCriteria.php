<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ArticleCriteria.
 */
class ArticleCriteria implements CriteriaInterface
{
    /**
     * 根据分类id筛选，
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $type = request('category_id');
        if ($type) {
            return $model->where('category_id',$type);
        }

        return $model;
    }
}
