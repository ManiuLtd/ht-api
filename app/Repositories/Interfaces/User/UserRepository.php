<?php

namespace App\Repositories\Interfaces\User;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 */
interface UserRepository extends RepositoryInterface
{
    /**
     * 粉丝.
     * @param int $level
     * @return mixed
     */
    public function getFrineds($level = 1);

    /**
     * 会员报表.
     * @return mixed
     */
    public function getUserChart();

    /**
     * 绑定手机号.
     * @return mixed
     */
    public function bindMobile();

    /**
     * 绑定邀请人.
     * @return mixed
     */
    public function bindInviter();


    public function register();

    /**
     * 根据邀请码查看用户信息
     * @return mixed
     */
    public function getInviter();

}
