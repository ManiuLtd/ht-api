<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Payment.
 */
class Payment extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'user_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'price',
        'transaction_id',
        'out_trade_no',
        'type',
        'status',
        'other',
        'payment_at',
    ];

    /**
     * @var array
     */
    protected $dates = ['payment_at'];

    /**
     * @var array
     */
    protected $hidden = [

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
