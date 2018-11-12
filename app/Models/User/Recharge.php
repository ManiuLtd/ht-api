<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Recharge.
 */
class Recharge extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'user_recharges';

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

        'merch_id',
        'order_id',
    ];

    /**
     * 所属用户.
     * @return bool|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User', 'user_id')->withDefault(null);
    }
}
