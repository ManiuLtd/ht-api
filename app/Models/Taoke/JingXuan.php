<?php

namespace App\Models\Taoke;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class JingXuan.
 *
 * @package namespace App\Models\Taoke;
 */
class JingXuan extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'tbk_jingxuan';

    protected static function boot()
    {
        parent::boot ();

        self::creating (function ($model) {
            $model->pic_url = json_encode (request ('pic_url'));
        });

        self::updating (function ($model) {
            $model->pic_url = json_encode (request ('pic_url'));
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        //TODO 写入所有可插入的字段
    ];

}
