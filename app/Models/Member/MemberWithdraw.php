<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MemberWithdraw.
 *
 * @package namespace App\Models;
 */
class MemberWithdraw extends Model implements Transformable
{
    use TransformableTrait;

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
     * 提现所属用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo ('App\Models\Member\Member', 'member_id')->withDefault (null);
    }

    /**
     * @var array
     */
    protected $hidden = [
        'member_id'
    ];
    /**
     * 字段映射
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }
}
