<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Haohuo
 * @package App\Models\Taoke
 */
class HaoHuo extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @return array
     */
    public function transform()
    {
        $array = $this->toArray();
        $array['data'] = json_decode($array['data']);
        return $array;
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

    /**
     * data是itemid的集合
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model){
            $model->data = json_encode(request('itemid'));
        });
        self::updating(function ($model){
            $model->data = json_encode(request('itemid'));
        });
    }
}