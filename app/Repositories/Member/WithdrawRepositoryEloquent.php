<?php

namespace App\Repositories\Member;

use App\Models\Member\Withdraw;
use App\Criteria\RequestCriteria;
use App\Tools\Taoke\Commission;
use App\Validators\Member\WithdrawValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Member\WithdrawRepository;

/**
 * Class WithdrawRepositoryEloquent.
 */
class WithdrawRepositoryEloquent extends BaseRepository implements WithdrawRepository
{

    /**
     * @var Commission
     */
    protected $commission;


    /**
     * WithdrawRepositoryEloquent constructor.
     * @param Commission $commission
     */
    public function __construct(Commission $commission)
    {
        $this->commission = $commission;

    }

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
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Withdraw::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return WithdrawValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
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
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * 申请提现
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw()
    {
        $memberID = getMemberId ();
        $member = db ('members')->find ($memberID);
        $amount = request ('amount');
        if ($amount > $member->credit1) {
            return json (4001, '可提现金额不足');
        }
        $withdraw = db ('member_withdraws')->orderBy ('id', 'desc')->where ([
            'member_id' => $memberID,
            'status' => 0
        ])->first ();

        if ($withdraw) {
            return json (4001, '已有在审核中的提现申请');
        }
        $data = [
            'member_id' => $memberID,
            'realname' => request ('realname'),
            'bankname' => request ('bankname'),
            'bankcard' => request ('bankcard'),
            'alipay' => request ('alipay'),
            'money' => request ('amount'),
            'status' => 0,
        ];
        try {
            db ('member_withdraws')->insert ($data);
            return json (1001, '申请提现成功，请等待审核');
        } catch (\Exception $e) {
            return json (5001, '提现申请失败');
        }
    }

    /**
     * TODO NEED REVIEW
     * 提现计算
     * @return array
     */
    public function getWithdrawData()
    {
        $id = getMemberId ();
        $member = db ('members')->find ($id);
        //本月预估收益
        //自推收益
        $self_commission = $this->commission->selfPush ($id);
        //下级收益
        $subordinate = $this->commission->subordinate ($id);
        //组长收益
        $leader = $this->commission->leader ($id);
        //当前用户是其他组的旧组长

        $old_leader = $this->commission->old_leader($id);
        $month_commission = $self_commission['money'] + $subordinate + $leader + $old_leader;


        //今天收益
        //自推收益
        $day_self_commission = $this->commission->selfPush ($id, 'day');
        //下级收益
        $day_subordinate = $this->commission->subordinate ($id, 'day');
        //组长收益
        $day_leader = $this->commission->leader ($id, 'day');
        //当前用户是其他组的旧组长

        $day_old_leader = $this->commission->old_leader($id,'day');
        $day_commission = $day_self_commission['money'] + $day_subordinate + $day_leader + $day_old_leader;


        $settlement = db ('member_credit_logs')->where ([
            'type' => 2,
            'credit_type' => 1,
        ])->whereYear ('created_at', now ()->year)
            ->whereMonth ('created_at', now ()->subMonth (1)->month)
            ->sum ('credit');

        return [
            'month_commission' => $month_commission,//本月预估
            'day_commission' => $day_commission,//今日收益
            'settlement' => $settlement,//上月结算
            'money' => $member->credit1 ?? 0,//可提现
        ];

    }
}
