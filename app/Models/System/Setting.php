<?php

namespace App\Models\System;

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
        'credit_order',
        'credit_friend',
        'notification',
        'pid',
        'withdraw',
        'taobao',
        'jingdong',
        'pinduouo',
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
            if (request('credit_order')) {
                $model->shop = json_encode(request('credit_order'));
            }
            if (request('credit_friend')) {
                $model->shop = json_encode(request('credit_friend'));
            }
            if (request('notification')) {
                $model->shop = json_encode(request('notification'));
            }
            if (request('pid')) {
                $model->shop = json_encode(request('pid'));
            }
            if (request('withdraw')) {
                $model->shop = json_encode(request('withdraw'));
            }
            if (request('taobao')) {
                $model->shop = json_encode(request('taobao'));
            }
            if (request('jingdong')) {
                $model->shop = json_encode(request('jingdong'));
            }
            if (request('pinduouo')) {
                $model->shop = json_encode(request('pinduouo'));
            }
        });
    }
}
