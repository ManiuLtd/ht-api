<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class History.
 */
class Dian extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'dian';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'inviter_id',
        'category_id',
        'thumb',
        'images',
        'name',
        'phone',
        'city',
        'address',
        'introduce',
        'tag',
        'hot',
        'commission_rate',
        'deduct_rate',
        'card1',
        'card2',
        'zhizhao',
        'can_pay',
    ];

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();
        //创建之前 添加member_id
        self::creating(function ($model) {
            $model->user_id = getUserId();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Taoke\DianCategories', 'category_id')->withDefault(null);
    }
}
