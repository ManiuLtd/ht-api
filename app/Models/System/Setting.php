<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Setting.
 */
class Setting extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment',
        'credit_order',
        'credit_friend',
        'notification',
        'pid',
        'withdraw',
        'level_desc',
        'download',
        'kuaizhan',
        'xieyi',
        'commission_rate',
    ];

    protected $hidden = [
        'user_id',
    ];


    public function transform()
    {
        return [
            'xieyi' => $this->xieyi,
            'level_desc' => $this->level_desc,
        ];
    }

    /**
     * 自动格式转换
     * @var array
     */
    protected $casts = [

        'payment' => 'array',
        'credit_order' => 'array',
        'credit_friend' => 'array',
        'notification' => 'array',
        'withdraw' => 'array',

    ];
}
