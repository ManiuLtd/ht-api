<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ShopGoodsTag.
 *
 * @package namespace App\Models\Shop;
 */
class ShopGoodsTag extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort',
        'color',
        'status'
    ];

    protected $dates = ['deleted_at'];


    /**
     * 字段映射
     * @return array
     */
    public function transform()
    {
        return $this->toArray ();
    }

}
