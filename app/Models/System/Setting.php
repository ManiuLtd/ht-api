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
        'enough_reduce',
        'enough_free',
        'deduction',
        'payment',
        'recharge',
        'shop',
        'credit',
        'credit_order',
        'credit_friend',
        'notification',
        'pid',
        'withdraw',
        'taobao',
        'jingdong',
        'pinduouo',
        'level_desc',
        'download',
    ];

    /**
     * 自动格式转换
     * @var array
     */
    protected $casts = [
        'enough_reduce' => 'array',
        'enough_free' => 'array',
        'deduction' => 'array',
        'payment' => 'array',
        'recharge' => 'array',
        'shop' => 'array',
        'credit' => 'array',
        'credit_order' => 'array',
        'credit_friend' => 'array',
        'notification' => 'array',
        'pid' => 'array',
        'withdraw' => 'array',
        'taobao' => 'array',
        'jingdong' => 'array',
        'pinduouo' => 'array',
        'unionid' => 'array',
        'download' => 'array',
    ];
}
