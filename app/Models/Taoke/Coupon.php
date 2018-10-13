<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Coupon
 * @package App\Models\Taoke
 */
class Coupon extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'tbk_coupons';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'cat',
        'shop_type',
        'pic_url',
        'item_id',
        'item_url',
        'volume',
        'price',
        'final_price',
        'coupon_price',
        'coupon_link',
        'activity_id',
        'commission_rate',
        'introduce',
        'total_num',
        'receive_num',
        'tag',
        'is_recommend',
        'type',
        'status',
        'start_time',
        'end_time'
    ];

}
