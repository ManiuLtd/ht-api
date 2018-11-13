<?php

namespace App\Repositories\User;

use App\Models\User\Address;
use App\Criteria\RequestCriteria;
use App\Validators\User\AddressValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\User\AddressRepository;

/**
 * Class AddressRepositoryEloquent.
 */
class AddressRepositoryEloquent extends BaseRepository implements AddressRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'userr_id',
        'user_id',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Address::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return AddressValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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
