<?php

namespace App\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RechargeRepository.
 */
interface RechargeRepository extends RepositoryInterface
{
    //
    public function pushCriteria($param);
}
