<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Setting.
 *
 * @package namespace App\Models\Taoke;
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
        'pinduouo',
        'user_id'
    ];

    /**
     * @var string
     */
    protected $table = 'tbk_settings';

    /**
     * 自动格式转换
     * @var array
     */
    protected $casts = [
        'pid' => 'array',
        'unionid' => 'array',
        'taobao' => 'array',
        'jingdong' => 'array',
        'pinduouo' => 'array',

    ];
}
