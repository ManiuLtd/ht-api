<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Withdraw.
 */
class Withdraw extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'user_withdraws';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'money',
        'deduct_money',
        'real_money',
        'pay_type',
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
