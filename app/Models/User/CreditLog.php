<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CreditLog.
 */
class CreditLog extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'user_credit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
        'operater_id',
    ];

    /**
     * 所属用户.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User\User')->withDefault(null);
    }
    public function transform()
    {

        if (!$type = request('type')){
            $type = $this->type;
        }
        return [
            'user_id' => $this->user_id,
            'credit' => $this->credit,
            'column' => $this->column,
            'remark' => $this->remark,
            'type' => $type,
            'current_credit' => $this->current_credit,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];


    }
}
