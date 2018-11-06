<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ArticleCategoryCriteria.
 */
class ArticleCategoryCriteria implements CriteriaInterface
{
    /**
     * 根据分类id筛选.
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $cateID = request('category_id');

        if ($cateID) {
            return $model->where('category_id', $cateID);
        }

        return $model;
    }
}
