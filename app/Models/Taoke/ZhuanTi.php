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

    /**
     * 添加更新前.
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->items = json_encode(request('items'));
        });
        self::updating(function ($model) {
            $model->items = json_encode(request('items'));
        });
    }
}
