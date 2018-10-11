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
    protected $guarded = [];

}
