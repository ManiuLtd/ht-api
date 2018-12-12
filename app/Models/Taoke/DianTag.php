<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class History.
 */
class DianTag extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'dian_tags';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'sort',
        'status',
    ];


}
