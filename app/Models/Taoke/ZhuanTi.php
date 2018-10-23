<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Category.
 */
class ZhuanTi extends Model implements Transformable
{
    use TransformableTrait;

    public function transform()
    {
        return $this->toArray();
    }

    protected $table = 'tbk_zhuanti';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
