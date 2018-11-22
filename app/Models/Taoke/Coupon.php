<?php

namespace App\Models\Taoke;

use App\Tools\Taoke\Commission;
use App\Tools\Taoke\TBKCommon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Coupon.
 */
class Coupon extends Model implements Transformable
{
    use TransformableTrait,
        TBKCommon;

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
     * 赚多少.
     * @return array
     */
    public function transform()
    {
        $user = getUser ();
        $data = new Commission();
//        $array = $this->toArray ();
        $finalCommission = $data->getCommissionByUser ($user->id, $this->final_price * $this->commission_rate / 100, 'commission_rate1');
        $array['price'] = floatval ($this->price);
        $array['final_price'] = floatval ($this->final_price);
        $array['coupon_price'] = floatval ($this->coupon_price);
        $array['finalCommission'] = floatval (round ($this->getFinalCommission ( floatval ($this->final_price) * $this->commission_rate / 100), 2));
        $array['title'] = $this->title;
        $array['pic_url'] = $this->pic_url;
        $array['item_id'] = $this->item_id;
        $array['price'] = $this->price;
        $array['final_price'] = $this->final_price;
        $array['coupon_price'] = $this->coupon_price;
        $array['commission_rate'] = $this->commission_rate;
        $array['type'] = $this->type;
        $array['volume'] = $this->volume;

        return $array;
    }
}
