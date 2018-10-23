<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Coupon.
 */
class Kuaiqiang extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tbk_kuaiqiang';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'itemid',
        'title',
        'short_title',
        'introduce',
        'final_price',
        'price',
        'sales',
        'pic_url',
        'today_sale',
        'shop_type',
        'cat',
        'coupon_price',
        'video_id',
        'video_url',
        'activity_type',
        'coupon_start_time',
        'coupon_end_time',
        'end_time',
        'start_time',
        'type',
    ];

    /**
     * @var array
     */
    protected $hidden = [

    ];

    protected $dates = ['deleted_at'];
}
