<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserCriteria.
 */
class UserCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository.
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        //获取userid
        $userID = getUserId();
        $type = request('type');
        if (in_array($type,[2,3,4])) {
            return $model->where('type',$type);
        }
        if ($userID){
            return $model->where([
                'user_id' => $userID,
            ]);
        }
        if ($userID && $type) {
            return $model->where([
                'user_id' => $userID,
                'type' => $type
            ]);
        }


        return $model;
    }
}
