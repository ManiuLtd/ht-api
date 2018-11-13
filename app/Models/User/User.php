<?php

namespace App\Models\User;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\ResetUserPassword;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, Transformable
{
    use Notifiable,
        SoftDeletes,
        TransformableTrait,
        LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inviter_id',
        'name',
        'email',
        'password',
        'nickname',
        'phone',
        'credit1',
        'credit2',
        'headimgurl',
        'status',
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string
     */
    protected $guarded = 'user';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

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
        //编辑是如果设置了密码 则更新密码
        if (request('password') != '') {
            self::updating(function ($model) {
                $model->password = bcrypt(request('password'));
            });
        }
    }

    /**
     * 使用验证码找回密码
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetUserPassword($token));
    }
    /**
     * 等级
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo('App\Models\User\User')->withDefault(null);
    }
}
