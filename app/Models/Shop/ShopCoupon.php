<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ShopCoupon
 * @package App\Models\Shop
 */
class ShopCoupon extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'thumb',
        'total',
        'max_receive',
        'enough',
        'color',
        'coupon_type',
        'is_show',
        'discount_type',
        'discount',
        'deduct',
        'limit_type',
        'limit_days',
        'time_start',
        'time_end',
        'is_limit_goods',
        'limit_goods_ids',
        'is_limit_category',
        'limit_category_ids',
        'is_limit_level',
        'limit_level_ids',
        'is_limit_agent',
        'limit_agent_ids',
        'description',
        'sort',
        'status'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'category_id',
        'user_id',
        'merch_id',
    ];

    protected $dates = ['deleted_at'];

    /**
     * 所属分类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo ('App\Models\Shop\ShopCouponCategory')->withDefault (null);
    }
}
