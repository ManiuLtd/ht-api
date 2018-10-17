<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Haohuo
 * @package App\Models\Taoke
 */
class Haohuo extends Model implements Transformable
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
    protected $table = 'tbk_haohuo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
