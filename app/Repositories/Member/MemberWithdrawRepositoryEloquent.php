<?php

namespace App\Repositories\Member;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\WithdrawRepository;
use App\Models\Member\MemberWithdraw;
use App\Validators\Member\MemberWithdrawValidator;

/**
 * Class MemberWithdrawRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MemberWithdrawRepositoryEloquent extends BaseRepository implements WithdrawRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'out_trade_no' => 'like',
        'member_id',
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
        return MemberWithdraw::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return MemberWithdrawValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
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
