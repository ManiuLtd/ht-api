<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Group.
 */
class Group extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'pid',
        'name',
        'qrcode',
        'qq',
        'wechat',
        'logo',
        'description',
        'status',
        'default',
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

    /**
     * 后端用户.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
