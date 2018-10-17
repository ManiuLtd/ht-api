<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class JingxuanDp.
 *
 * @package namespace App\Models\Taoke;
 */
class JingxuanDp extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'tbk_jingxuan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
