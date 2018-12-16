<?php

namespace App\Repositories\Taoke;

use App\Criteria\RequestCriteria;
use App\Models\Taoke\DianCategories;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Validators\Taoke\DianCategoriesValidator;
use App\Repositories\Interfaces\Taoke\DianCategoriesRepository;

/**
 * Class DianRepositoryEloquent.
 */
class DianCategoriesRepositoryEloquent extends BaseRepository implements DianCategoriesRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return DianCategories::class;
    }

    /**
     * @return null|string
     */
    public function validator()
    {
        return DianCategoriesValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
