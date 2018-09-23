<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MemberWithdraw.
 */
class MemberWithdraw extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = "member_withdraws";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deduct_money',
        'pay_type',
        'pay_time',
        'refused_time',
        'reason',
        'status',
    ];

    /**
     * 提现所属用户.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo('App\Models\Member\Member', 'member_id')->withDefault(null);
    }

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
        'member_id',
    ];
}
