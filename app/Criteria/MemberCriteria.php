<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Tymon\JWTAuth\Contracts\Providers\Auth;

/**
 * Class MemberCriteria.
 *
 * @package namespace App\Criteria;
 */
class MemberCriteria implements CriteriaInterface
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
        //获取memberid
        $memberID = getMemberId();

        if ($memberID) {
            return $model->where('member_id', $memberID);
        }
        return $model;
    }
}
