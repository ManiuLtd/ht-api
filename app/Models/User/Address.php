<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Address.
 */
class Address extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'user_addresses';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'member_id',
        'realname',
        'phone',
        'province',
        'city',
        'area',
        'address',
        'zipcode',
        'isdefault',
        'type',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $hidden = [
        'member_id',
        'user_id',
    ];
}
