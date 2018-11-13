<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Favourite;
use App\Criteria\RequestCriteria;
use App\Validators\Taoke\FavouriteValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\FavouriteRepository;

/**
 * Class FavouriteRepositoryEloquent.
 */
class FavouriteRepositoryEloquent extends BaseRepository implements FavouriteRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Favourite::class;
    }

    /**
     * @return null|string
     */
    public function validator()
    {
        return FavouriteValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
