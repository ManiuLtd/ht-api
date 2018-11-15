<?php

namespace App\Repositories\Interfaces\System;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface SettingRepository.
 */
interface SettingRepository extends RepositoryInterface
{
    //成长值途径列表
    public function getPath();
}
