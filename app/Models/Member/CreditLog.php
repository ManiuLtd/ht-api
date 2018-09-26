<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CreditLog.
 */
class CreditLog extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'member_credit_logs';

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
        'operater_id',
    ];

    /**
     * 所属会员.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo('App\Models\Member\Member')->withDefault(null);
    }

    /**
     * 所属用户.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User')->withDefault(null);
    }
}
