<?php

namespace App\Repositories\Interfaces\Member;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MemberRepository.
 */
interface MemberRepository extends RepositoryInterface
{
    public function getFrineds($level = 1);
}
