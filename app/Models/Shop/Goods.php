<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Goods.
 */
class Goods extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'shop_goods';

    /**
     * @var array
     */
    protected $guarded = [
        'user_id',
        'merch_id',
        'categories', //分类
        'specs', //多规格
        'params' //参数
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
        'merch_id',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot ();

        //创建
        self::creating (function ($model) {
            $model->thumb = json_encode ($model->thumb);
            //TODO 判断是否为多商户 以下代码为单商户的
            $model->user_id = getUserId ();
        });

        //更新
        self::updating (function ($model) {
            $model->thumb = json_encode ($model->thumb);
        });
    }

    /**
     * 产品所属分类.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany ('App\Models\Shop\Category', 'shop_category_shop_goods', 'goods_id', 'category_id');
    }

    /**
     * 商品规格
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function params()
    {
      return $this->hasMany ('App\Models\Shop\GoodsParams');
    }

    /**
     * 处理返回的字段信息
     * @return array
     */
    public function transform()
    {
        $array = $this->toArray ();
        $array['thumb'] = json_decode ($array['thumb']);

        return $array;
    }
}
