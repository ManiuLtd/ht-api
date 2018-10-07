<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Quan.
 */
class Quan extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }

    /**
     * @var string
     */
    protected $table = 'tbk_quan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $hidden = [
        'user_id',
    ];

    /**
     * 后台用户.
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User', 'user_id')->withDefault(null);
    }

    /**
     * 前端用户.
     * @return $this
     */
    public function goods()
    {
        return $this->belongsTo('App\Models\Shop\Goods', 'item_id')->withDefault(null);
    }
}
