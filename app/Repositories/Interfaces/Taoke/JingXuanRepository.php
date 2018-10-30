<?php

namespace App\Repositories\Interfaces\Taoke;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface JingXuanRepository.
 */
interface JingXuanRepository extends RepositoryInterface
{
    /**
     * 淘口令转换
     * @return mixed
     */
    public function TaoCommand();
}
