<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MemberRecharge.
 */
class MemberRecharge extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'member_recharges';

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
        'user_id',
        'member_id',
        'merch_id',
        'order_id',
    ];

    /**
     * 所属用户.
     * @return bool|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo('App\Models\Member\Member', 'member_id')->withDefault(null);
    }
}
