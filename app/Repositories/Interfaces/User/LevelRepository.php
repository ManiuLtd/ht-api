<?php

namespace App\Repositories\Interfaces\User;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface LevelRepository.
 */
interface LevelRepository extends RepositoryInterface
{
    /**
     * 付费升级
     * @return mixed
     */
    public function payment();
}
