<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Coupon.
 */
class Coupon extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'tbk_coupons';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 关联产品.
     * @return $this
     */
    public function goods()
    {
        return $this->belongsTo('App\Models\Shop\Goods', 'item_id')->withDefault(null);
    }

}
