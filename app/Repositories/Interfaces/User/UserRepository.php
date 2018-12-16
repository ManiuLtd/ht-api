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
     * @return mixed
     */
    public function getFrineds();

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

    /**
     * 注册.
     * @return mixed
     */
    public function register();

    /**
     * 手动升级.
     * @return mixed
     */
    public function checkUpgrade();

    /**
     * 根据邀请码查看用户信息.
     * @return mixed
     */
    public function getInviter();

    /**
     * @param $user
     * @param $inviter
     * @return mixed
     */
    public function bindinviterRegister($user, $inviter);

    /**
     * 绑定支付宝.
     * @return mixed
     */
    public function bindAlipay();

    /**
     * 会员报表.
     * @return mixed
     */
    public function chart();
}
