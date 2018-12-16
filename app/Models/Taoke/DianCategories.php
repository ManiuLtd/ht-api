<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class History.
 */
class DianCategories extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'dian_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'thumb',
        'sort',
        'status',
    ];

    /*
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
}
