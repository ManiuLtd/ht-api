<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Setting.
 */
class Setting extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enough_reduce',
        'enough_free',
        'deduction',
        'payment',
        'recharge',
        'shop',
        'credit',
    ];

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();

        self::updating(function ($model) {
            if (request('enough_reduce')) {
                $model->enough_reduce = json_encode(request('enough_reduce'));
            }
            if (request('enough_free')) {
                $model->enough_free = json_encode(request('enough_free'));
            }
            if (request('deduction')) {
                $model->deduction = json_encode(request('deduction'));
            }
            if (request('payment')) {
                $model->payment = json_encode(request('payment'));
            }
            if (request('recharge')) {
                $model->recharge = json_encode(request('recharge'));
            }
            if (request('credit')) {
                $model->credit = json_encode(request('credit'));
            }
            if (request('shop')) {
                $model->shop = json_encode(request('shop'));
            }
        });
    }
}
