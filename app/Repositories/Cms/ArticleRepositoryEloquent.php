<?php

namespace App\Repositories\Cms;

use App\Models\Cms\Article;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Cms\ArticleRepository;
use App\Validators\Cms\ArticleValidator;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CategoriesRepositoryEloquent.
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
}
