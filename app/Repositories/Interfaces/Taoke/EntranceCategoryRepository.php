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
     * 分类列表
     * @return mixed
     */
    public function list();
}
