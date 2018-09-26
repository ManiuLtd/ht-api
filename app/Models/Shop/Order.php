<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Order.
 */
class Order extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'shop_orders';

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
        'member_id',
        'address_id',
        'user_id',
        'merch_id',
        'agent_id',
    ];

    /**
     * 子订单.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subOrders()
    {
        return $this->hasMany('App\Models\Shop\OrderGoods', 'order_id');
    }

    /**
     * 所属用户.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo('App\Models\Member\Member')->withDefault(null);
    }

    /**
     * 订单对应的收货地址
     * @return bool|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo('App\Models\Member\Address', 'address_id')->withDefault(null);
    }
}
