<?php

namespace App\Repositories\Taoke;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\CouponRepository;
use App\Models\Taoke\Coupon;
use App\Validators\Taoke\CouponValidator;

/**
 * Class CouponRepositoryEloquent.
 *
 * @package namespace App\Repositories\Taoke;
 */
class CouponRepositoryEloquent extends BaseRepository implements CouponRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Coupon::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return CouponValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
