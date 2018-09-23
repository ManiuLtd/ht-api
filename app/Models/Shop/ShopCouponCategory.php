<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ShopCouponCategory.
 */
class ShopCouponCategory extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort',
        'status',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
        'merch_id',
    ];
}
