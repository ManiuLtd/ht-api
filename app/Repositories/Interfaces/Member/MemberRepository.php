<?php

namespace App\Repositories\Interfaces\Member;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MemberRepository.
 */
interface MemberRepository extends RepositoryInterface
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
    public function getMemberChart();

    /**
     * 绑定手机号
     * @return mixed
     */
    public function bindMobile();

    /**
     * 绑定邀请人
     * @return mixed
     */
    public function bindInviter();

}
