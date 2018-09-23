<?php

namespace App\Repositories\Member;

use App\Criteria\RequestCriteria;
use App\Models\Member\MemberAddress;
use App\Repositories\Interfaces\MemberAddressRepository;
use App\Validators\Member\MemberAddressValidator;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class MemberAddressRepositoryEloquent.
 */
class MemberAddressRepositoryEloquent extends BaseRepository implements MemberAddressRepository
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
        return MemberAddress::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return MemberAddressValidator::class;
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
