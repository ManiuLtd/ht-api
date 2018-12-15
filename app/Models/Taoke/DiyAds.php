<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class History.
 */
class DiyAds extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'tbk_diy_ads';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'thumb',
        'url',
        'sort',
        'status',
        'type',
    ];
}
