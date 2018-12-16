<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class History.
 */
class DianOrder extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'dian_orders';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'dian_id',
        'transaction_id',
        'out_trade_no',
        'title',
        'remark',
        'money',
        'real_money',
        'status',
        'refund_status',
        'pay_type',
        'payment_at',
    ];

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();
        //创建之前 添加member_id
        self::creating(function ($model) {
            $model->user_id = getUserId();
        });
    }
}
