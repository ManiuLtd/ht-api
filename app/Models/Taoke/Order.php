<?php

namespace App\Models\Taoke;

use App\Events\SendNotification;
use App\Events\CreditOrder;
use App\Models\User\User;
use App\Tools\Taoke\TBKCommon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Order.
 */
class Order extends Model implements Transformable
{
    use TransformableTrait,
        TBKCommon;

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
        // 创建订单时候,根据订单状态调用事件

        self::creating(function ($model) {

        });
        //更新订单的时候,如果状态有变化根据状态变化调用事件,对用户增减积分
        self::updating(function ($model) {
            
        });
        

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


    /**
     * @return array
     */
    public function transform()
    {
        $data = $this->toArray();
        $data['commission_amount'] = floatval (round ($this->getFinalCommission (floatval($this->commission_amount)), 2));
        return $data;
    }

}
