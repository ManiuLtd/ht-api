<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ShopGoodsCategory.
 *
 * @package namespace App\Models\Shop;
 */
class ShopGoodsCategory extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parentid',
        'name',
        'thumb',
        'description',
        'advimg',
        'advurl',
        'status',
        'ishome',
        'isrecommand'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'user_id'
    ];

    /**
     * 字段映射
     * @return array
     */
    public function transform()
    {
        return $this->toArray ();
    }

}
