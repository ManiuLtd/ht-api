<?php

namespace App\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RechargeRepository.
 *
 * @package namespace App\Repositories\Interfaces;
 */
interface RechargeRepository extends RepositoryInterface
{
    //
    public function pushCriteria($param);
}
