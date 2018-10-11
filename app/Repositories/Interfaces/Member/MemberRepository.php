<?php

namespace App\Repositories\Interfaces\Member;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MemberRepository.
 */
interface MemberRepository extends RepositoryInterface
{
    /**
     * @param int $level
     * @return mixed
     */
    public function getFrineds($level = 1);

    /**
     * @return mixed
     */
    public function getTeamCharts();

    /**
     * @return mixed
     */
    public function promotionLevel();
}
