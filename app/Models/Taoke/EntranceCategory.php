<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class EntranceCategory
 * @package App\Models\Taoke
 */
class EntranceCategory extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tbk_entrance_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'sort',
        'status',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entrance()
    {
        return $this->hasMany('App\Models\Taoke\Entrance','category_id');
    }

    /**
     * 子分类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function children()
    {
        return $this->belongsTo('App\Models\Taoke\EntranceCategory','parent_id')->withDefault (null);
    }
}
