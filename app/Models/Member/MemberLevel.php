<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MemberLevel.
 */
class MemberLevel extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'member_levels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo',
        'level',
        'discount',
        'ordermoney',
        'ordernum',
        'sort',
        'status',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];
}
