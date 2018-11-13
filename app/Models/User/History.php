<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class History.
 */
class History extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'user_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $hidden = [
        'user_id',
        'merch_id',
        'goods_id',
    ];

    /**
     * 所属商品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|string
     */
    public function goods()
    {
        return $this->belongsTo('App\Models\Shop\Goods', 'goods_id')->withDefault(null);
    }
}
