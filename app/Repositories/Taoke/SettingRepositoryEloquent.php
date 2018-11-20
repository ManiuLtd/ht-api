<?php

namespace App\Repositories\Taoke;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\SettingRepository;
use App\Models\Taoke\Setting;
use App\Validators\Taoke\SettingValidator;

/**
 * Class SettingRepositoryEloquent.
 *
 * @package namespace App\Repositories\Taoke;
 */
class SettingRepositoryEloquent extends BaseRepository implements SettingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Setting::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return SettingValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
