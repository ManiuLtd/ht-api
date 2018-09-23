<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MemberAddress.
 */
class MemberAddress extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

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
