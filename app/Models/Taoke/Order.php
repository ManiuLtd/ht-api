<?php

namespace App\Models\Taoke;

use App\Events\SendOrder;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Order.
 */
class Order extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'tbk_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();
        //TODO 这里代码是什么意思
//        //创建订单时候,根据订单状态调用事件
//        if (request('status') == 3 && request('member_id')) {
//            self::creating(function ($model) {
//                event(new SendOrder([
//                    'user_id' => request('member_id')
//                ]));
//            });
//            //TODO 发送推送
//        }
//
//        //更新订单的时候,如果状态有变化根据状态变化调用事件,对用户增减积分
//        if (request('status') == 3 && request('member_id')) {
//            self::updating(function ($model) {
//                event(new SendOrder([
//                    'user_id' => request('member_id')
//                ]));
//            });
//            //TODO 发送推送
//        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User')->withDefault(null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\User\Group', 'group_id')->withDefault(null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function oldGroup()
    {
        return $this->belongsTo('App\Models\User\Group', 'oldgroup_id')->withDefault(null);
    }
}
