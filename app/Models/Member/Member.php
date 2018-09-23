<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Member.
 */
class Member extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inviter_id',
        'phone',
        'password',
        'credit1',
        'credit2',
        'level1',
        'level2',
        'status',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
        'inviter_id',
        'level1',
        'level2',
        'password',
    ];

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();
        //创建之前 加密密码
        self::creating(function ($model) {
            $model->password = bcrypt(request('password'));
        });

        //编辑用户时 如果设置了密码  则更新密码
        if (request('password') != '') {
            self::updating(function ($model) {
                $model->password = bcrypt(request('password'));
            });
        }
    }

    /**
     * 当前用户邀请人.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|string
     */
    public function inviter()
    {
        return $this->belongsTo(self::class, 'inviter_id')->withDefault(null);
    }

    /**
     * 当前用户会员等级.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|string
     */
    public function level()
    {
        return $this->belongsTo('App\Models\Member\MemberLevel', 'level1')->withDefault(null);
    }

    /**
     * 用户收货地址
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(MemberAddress::class);
    }

    /**
     * 用户订单.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    /**
     * 用户评论.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
}
