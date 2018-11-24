<?php

namespace App\Criteria\User;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CreditLogsCriteria.
 *
 * @package namespace App\Criteria\User;
 */
class CreditLogsCriteria implements CriteriaInterface
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
        $type = request('type');
        $userID = getUserId();
        // 支出
        if ($type == 1) {
            $model = $model->whereIn('type',[12,14]);
        }
        //收入
        if ($type == 2) {
            $model = $model->whereIn('type',[11,13,15,22,18]);
        }
        if ($userID) {
            $model = $model->where('user_id', $userID);
        }
        return $model;
    }
}
