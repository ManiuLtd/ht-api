<?php

namespace App\Repositories\Interfaces\Member;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CreditLogRepository.
 */
interface CreditLogRepository extends RepositoryInterface
{
    //
    /**
     * @return mixed
     */
    public function getWithdrawCharts();
}
