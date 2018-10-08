<?php

namespace App\Repositories\Interfaces\Member;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface WithdrawRepository.
 */
interface WithdrawRepository extends RepositoryInterface
{
    //

    public function getWithdrawData();
    public function withdraw();


}
