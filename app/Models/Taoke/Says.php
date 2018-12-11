<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Says
 * @package App\Models\Taoke
 */
class Says extends Model implements Transformable
{
    use TransformableTrait,SoftDeletes;

    protected $table = 'tbk_says';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function transform()
    {
        $array = $this->toArray();
//        $array['tk_item_id'] = json_decode($array['tk_item_id']);
        $array['tk_item_id'] = explode(',',$array['tk_item_id']);

        return $array;
    }

}
