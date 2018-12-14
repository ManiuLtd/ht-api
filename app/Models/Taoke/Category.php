<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Category.
 */
class Category extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tbk_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',
        'logo',
        'taobao',
        'jingdong',
        'pinduoduo',
        'sort',
        'type',
        'status',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 子分类
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany('App\Models\Taoke\Category','parent_id');
    }
}
