<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ZhuanTi
 * @package App\Models\Taoke
 */
class ZhuanTi extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'tbk_zhuanti';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function transform()
    {
        $array = $this->toArray();
        $array['items'] = json_decode($array['items']);

        return $array;
    }

}
