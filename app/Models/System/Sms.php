<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Sms.
 */
class Sms extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'sms';

    /**
     * @var array
     */
    protected $guarded = [];


    /**
     * 字段映射.
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }

}
