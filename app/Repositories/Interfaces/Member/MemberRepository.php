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
}
