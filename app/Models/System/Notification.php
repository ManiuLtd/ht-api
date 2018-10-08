<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Notification.
 */
class Notification extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'notifications';
    protected $guarded = [];

    protected $hidden = [
        'user_id',
    ];

    /**
     * 字段映射.
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }


    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();
        //创建之前
        self::creating(function ($model) {
            $model->member_id = getMemberId();
            $model->user_id = getUserId();
            $model->sendno = 'HT'.rand(10000,99999);
        });
    }
    /**
     * 后台用户.
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User', 'user_id')->withDefault(null);
    }

    /**
     * 前端用户.
     * @return $this
     */
    public function member()
    {
        return $this->belongsTo('App\Models\Member\Member', 'member_id')->withDefault(null);
    }
}
