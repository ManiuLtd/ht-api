<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class MemberFavourite.
 *
 * @package namespace App\Models\Member;
 */
class MemberFavourite extends Model implements Transformable
{
    use TransformableTrait,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'member_favourites';
    protected $fillable = [
        'user_id',
        'member_id',
        'merch_id',
        'goods_id'
    ];
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'member_id',
        'user_id'
    ];

}
