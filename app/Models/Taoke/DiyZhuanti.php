<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class History.
 */
class DiyZhuanti extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'tbk_diy_zhuanti';

    /**
     * @var array
     */
    protected $fillable = [
        'pid',
        'name',
        'params',
        'thumb',
        'list_thumb',
        'bgimg',
        'show_category',
        'limit',
        'sort',
        'status',
        'layout1',
        'layout2',
    ];

    /**
     * 子分类
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany('App\Models\Taoke\DiyZhuanti','pid');
    }



}
