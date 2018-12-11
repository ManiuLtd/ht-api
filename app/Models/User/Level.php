<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Level.
 */
class Level extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'user_levels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level',
        'name',
        'logo',
        'group_rate1',
        'group_rate2',
        'commission_rate1',
        'commission_rate2',
        'credit',
        'price1',
        'price2',
        'price3',
        'price4',
        'is_commission',
        'is_group',
        'is_pid',
        'default',
        'status',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];
}
