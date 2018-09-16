<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ShopCouponLog.
 *
 * @package namespace App\Models\Shop;
 */
class ShopCouponLog extends Model implements Transformable
{
    use TransformableTrait;


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
        'coupon_id',
        'member_id'
    ];

    /**
     * 关联优惠券
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo ('App\Models\Shop\ShopCoupon')->withDefault (null);
    }

    /**
     * 所属用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo ('App\Models\Member\Member')->withDefault (null);
    }

    /**
     * @return array
     */
    public function transform()
    {
        return [
            'id' => (int)$this->id,
            'ordersn' => $this->ordersn,
            'coupon_id' => $this->coupon_id,//优惠券ID
            'name' => $this->name,
            'thumb' => $this->thumb,
            'get_time' => $this->get_time,//获取时间
            'created_at' => $this->created_at->toDateTimeString (),
            'member_nickname' => $this->member->nickname,
            'coupon_deduct' => $this->coupon->deduct,// 立减金额
        ];


    }


}
