<?php

namespace App\Models\Taoke;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class JingxuanDp.
 *
 * @package namespace App\Models\Taoke;
 */
class JingxuanDp extends Model implements Transformable
{
    use TransformableTrait;

    public function transform()
    {
        return [
            'id'      => (int) $this->id,
            'title'   => $this->title,
            'pic_url' => $this->pic_url,
            'content' => $this->content,
            'price' => $this->price,
            'final_price' => $this->final_price,
            'coupon_price' => $this->coupon_price,
            'commission_rate' => $this->commission_rate,
            'shares' => $this->shares,
            'show_content' => $this->show_content,
            'copy_content' => $this->copy_content,
            'show_comment' => $this->show_comment,
            'copy_comment' => $this->copy_comment,
            'show_at' => $this->show_at,
            'created_at' => $this->created_at instanceof Carbon ? $this->created_at->toDateTimeString() : $this->created_at,
        ];
    }

    protected $table = 'tbk_jingxuan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

}
