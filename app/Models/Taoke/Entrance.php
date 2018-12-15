<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Entrance
 * @package App\Models\Taoke
 */
class Entrance extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tbk_entrances';

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'logo',
        'title',
        'descrtption',
        'url',
        'type',
        'is_home',
        'params',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 超级入口关联分类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo ('App\Models\Taoke\EntranceCategory', 'category_id')->withDefault (null);
    }

}
