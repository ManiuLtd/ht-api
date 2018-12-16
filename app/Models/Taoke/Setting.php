<?php

namespace App\Models\Taoke;

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
        'pid',
        'unionid',
        'taobao',
        'jingdong',
        'pinduoduo',
        'user_id',
    ];

    /**
     * @var string
     */
    protected $table = 'tbk_settings';

    protected $hidden = [
        'user_id',
    ];

    /**
     * 自动格式转换.
     * @var array
     */
    protected $casts = [
        'pid' => 'array',
        'unionid' => 'array',
        'taobao' => 'array',
        'jingdong' => 'array',
        'pinduoduo' => 'array',

    ];
}
