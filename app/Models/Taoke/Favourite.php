<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Favourite.
 */
class Favourite extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'tbk_user_favourites';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'pic_url',
        'item_id',
        'volume',
        'price',
        'coupon_price',
        'final_price',
        'type',
    ];

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->user_id = getUserId();
        });
    }
}
