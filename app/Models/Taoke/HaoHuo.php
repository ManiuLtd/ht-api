<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Haohuo.
 */
class HaoHuo extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * 根据itemid获取商品
     * @return array
     */
    public function transform()
    {
        $array = $this->toArray();
        $array['items'] = json_decode($array['items']);

        return $array;
    }

    /**
     * @var string
     */
    protected $table = 'tbk_haohuo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'app_hot_image',
        'text',
        'shares',
        'items',
        'start_time',
        'end_time',
    ];

    /**
     * data是itemid的集合.
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->items = json_encode(request('itemid'));
        });
//        self::updating(function ($model) {
//            $model->itemid = json_encode(request('itemid'));
//        });
    }
}
