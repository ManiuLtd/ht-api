<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CouponLog.
 */
class CouponLog extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'shop_coupon_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array
     */
    protected $hidden = [
        'merch_id',
        'user_id',
        'coupon_id',
    ];

    /**
     * 关联优惠券.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo('App\Models\Shop\Coupon')->withDefault(null);
    }

    /**
     * 所属用户.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User')->withDefault(null);
    }
}
