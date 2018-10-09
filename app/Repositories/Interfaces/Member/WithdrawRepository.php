<?php

namespace App\Repositories\Interfaces\Member;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface WithdrawRepository.
 */
interface WithdrawRepository extends RepositoryInterface
{

    /**
     * 获取提现信息
     * @return mixed
     */
    public function getWithdrawData();

    /**
     * 发起提现
     * @return mixed
     */
    public function withdraw();


}
