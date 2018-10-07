<?php

namespace App\Repositories\Cms;

use App\Models\Cms\Article;
use App\Criteria\RequestCriteria;
use App\Validators\Cms\ArticleValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Cms\ArticleRepository;

/**
 * Class ArticleRepositoryEloquent.
 */
class ArticleRepositoryEloquent extends BaseRepository implements ArticleRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'category_id',
        'title'=>'like',
        'keywords'=>'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Article::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return ArticleValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    public function type_list($type = null)
    {
        if($type){
            $article = $this->model
                ->where('category_id',$type)
                ->with(['user', 'category'])
                ->paginate(request('limit', 10));
        }else{
            $article = $this->model
                ->with(['user', 'category'])
                ->paginate(request('limit', 10));
        }

        return json(1001, '列表获取成功', $article);
    }
}
