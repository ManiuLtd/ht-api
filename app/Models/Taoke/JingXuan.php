<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class JingXuan.
 */
class JingXuan extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'tbk_jingxuan';

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->pic_url = json_encode(request('pic_url'));
        });

        self::updating(function ($model) {
            $model->pic_url = json_encode(request('pic_url'));
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'itemid',
        'title',
        'pic_url',
        'content',
        'price',
        'final_price',
        'coupon_price',
        'commission_rate',
        'shares',
        'comment1',
        'comment2',

    ];
}
