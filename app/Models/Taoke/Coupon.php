<?php

namespace App\Models\Taoke;

use App\Tools\Taoke\Commission;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Coupon.
 */
class Coupon extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'tbk_coupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'cat',
        'shop_type',
        'pic_url',
        'item_id',
        'item_url',
        'volume',
        'price',
        'final_price',
        'coupon_price',
        'coupon_link',
        'activity_id',
        'commission_rate',
        'introduce',
        'total_num',
        'receive_num',
        'tag',
        'is_recommend',
        'type',
        'status',
        'start_time',
        'end_time',
    ];
    /**
     * 赚多少
     * @return array
     */
    public function transform()
    {
        $member = getMember();
        $data = new Commission();
        $array = $this->toArray();
        $gain_price = $data->getComminnsionByUser($member->id,$this->final_price*$this->commission_rate/100,'commission_rate1');
        $array['gain_price'] = round($gain_price,2);
        return $array;
    }
}
