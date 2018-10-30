<?php

namespace App\Models\Taoke;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Category.
 */
class ZhuanTi extends Model implements Transformable
{
    use TransformableTrait;

    public function transform()
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'thumb'      => 'http://img.haodanku.com/'.$this->thumb,
            'banner'     => 'http://img.haodanku.com/'.$this->banner,
            'content'    => $this->content,
            'start_time' => $this->start_time,
            'end_time'   => $this->end_time,
        ];
    }

    protected $table = 'tbk_zhuanti';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
