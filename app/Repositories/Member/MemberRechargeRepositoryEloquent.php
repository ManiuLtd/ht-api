<?php

namespace App\Repositories\Member;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\RechargeRepository;
use App\Models\Member\MemberRecharge;
use App\Validators\Member\MemberRechargeValidator;

/**
 * Class MemberRechargeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MemberRechargeRepositoryEloquent extends BaseRepository implements RechargeRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'out_trade_no' => 'like',
        'member_id',
        'user_id',
        'type',
        'status',
        'created_at',
    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MemberRecharge::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return MemberRechargeValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria (app (RequestCriteria::class));
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return "Prettus\\Repository\\Presenter\\ModelFractalPresenter";
    }
}
