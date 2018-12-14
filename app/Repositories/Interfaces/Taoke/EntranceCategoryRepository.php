<?php

namespace App\Repositories\Interfaces\Taoke;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface EntranceCategoryRepository
 * @package App\Repositories\Interfaces\Taoke
 */
interface EntranceCategoryRepository extends RepositoryInterface
{
    /**
     * 超级入口列表
     * @return mixed
     */
    public function list();
}
