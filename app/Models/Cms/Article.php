<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Article.
 */
class Article extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = 'cms_articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 字段映射.
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
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
     * 分类.
     * @return mixed
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Cms\Category', 'category_id')->withDefault(null);
    }
}
