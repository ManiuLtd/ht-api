<?php

namespace App\Repositories\Member;

use App\Models\Member\Favourite;
use App\Criteria\RequestCriteria;
use App\Validators\Member\FavouriteValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Member\FavouriteRepository;

/**
 * Class FavouriteRepositoryEloquent.
 */
class FavouriteRepositoryEloquent extends BaseRepository implements FavouriteRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
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
