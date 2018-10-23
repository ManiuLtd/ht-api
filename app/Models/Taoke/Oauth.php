<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Oauth.
 */
class Oauth extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'tbk_oauth';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sid',
        'taoid',
        'name',
        'auth_time',
        'expire_time',
        'type',
        'user_id',
        'token',
        'refresh_token',
    ];
}
