<?php

namespace App\Repositories\Member;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\MemberFavouriteRepository;
use App\Models\Member\MemberFavourite;
use App\Validators\Member\MemberFavouriteValidator;

/**
 * Class MemberFavouriteRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MemberFavouriteRepositoryEloquent extends BaseRepository implements MemberFavouriteRepository
{

    protected $fieldSearchable = [
        'member_id',
        'user_id',
    ];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MemberFavourite::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return MemberFavouriteValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria (app (RequestCriteria::class));
    }

}
