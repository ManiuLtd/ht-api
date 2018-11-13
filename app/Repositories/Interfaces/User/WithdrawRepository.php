<?php

namespace App\Repositories\Interfaces\User;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface WithdrawRepository.
 */
interface WithdrawRepository extends RepositoryInterface
{
    //

    /**
     * @return mixed
     */
    public function getWithdrawChart();
}
