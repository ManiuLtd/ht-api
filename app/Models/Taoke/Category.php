<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Category.
 *
 * @package namespace App\Models\Taoke;
 */
class Category extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    public function transform()
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'sort' => $this->sort,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),

        ];
    }

    protected $table = 'tbk_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $dates = ['deleted_at'];

}
