<?php

namespace App\Models\Image;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Banner.
 */
class Banner extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'sort',
        'tag',
        'status',
    ];

    protected $hidden = [
        'user_id',
    ];
    //user关联
    public function user()
    {
        return $this->belongsTo('App\Models\User\User','user_id')->withDefault(null);
    }

    /**
     * 字段映射.
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }
}
