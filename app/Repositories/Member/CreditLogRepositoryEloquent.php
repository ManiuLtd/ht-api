<?php

namespace App\Repositories\Member;

use App\Models\Member\CreditLog;
use App\Criteria\RequestCriteria;
use App\Tools\Taoke\Commission;
use App\Validators\Member\CreditLogValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Member\CreditLogRepository;

/**
 * Class CreditLogRepositoryEloquent.
 */
class CreditLogRepositoryEloquent extends BaseRepository implements CreditLogRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'user_id',
        'type',
        'created_at',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return CreditLog::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return CreditLogValidator::class;
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

    //TODO
    public function getWithdrawCharts()
    {
        $type = request('type', 1);
        $member = getMember();
        $commission = new Commission();

        //待结算
        if ($type == 1) {

            //自推收益
            $self_commission = $commission->selfPush ($member->id,'',1);
            //下级收益
            $subordinate = $commission->subordinate ($member->id,'',1);
            //组长收益
            $leader = $commission->leader ($member->id,'',1);
            //当前用户是其他组的旧组长
            $old_leader = $commission->old_leader($member->id,'',1);

            $month_commission = $self_commission + $subordinate + $leader + $old_leader;
            return [
                'money'=>$month_commission,
            ];
        }
        //累计结算
        if ($type == 2) {
            $settled = $this->findWhere([
                'type' => 2,
                'credit_type' => 1
            ]);
            return data_get($settled,'data',[]);
        }
        // 累计提现
        if ($type == 3) {
            $settled = $this->findWhere([
                'type' => 4,
                'credit_type' => 1
            ]);

            return data_get($settled,'data',[]);
        }
    }
}
