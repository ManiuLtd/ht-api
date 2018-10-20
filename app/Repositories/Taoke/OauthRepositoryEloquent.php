<?php

namespace App\Repositories\Taoke;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\OauthRepository;
use App\Models\Taoke\Oauth;
use App\Validators\Taoke\OauthValidator;

/**
 * Class OauthRepositoryEloquent.
 *
 * @package namespace App\Repositories\Taoke;
 */
class OauthRepositoryEloquent extends BaseRepository implements OauthRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Oauth::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
