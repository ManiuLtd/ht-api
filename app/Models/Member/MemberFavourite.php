<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MemberFavourite.
 */
class MemberFavourite extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'member_favourites';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'member_id',
        'merch_id',
        'goods_id',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $hidden = [
        'member_id',
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
