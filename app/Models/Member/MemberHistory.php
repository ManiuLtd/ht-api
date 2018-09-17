<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MemberHistory.
 *
 * @package namespace App\Models\Member;
 */
class MemberHistory extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $hidden = [
        'user_id',
        'member_id',
        'merch_id',
        'goods_id',
    ];

    /**
     * 所属商品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|string
     */
    public function goods()
    {
        return $this->belongsTo ('App\Models\Shop\ShopGoods', 'goods_id')->withDefault (null);
    }

}
