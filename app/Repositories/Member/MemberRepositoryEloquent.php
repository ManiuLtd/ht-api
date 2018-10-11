<?php

namespace App\Repositories\Member;

use App\Models\Member\Level;
use App\Models\Member\Member;
use App\Criteria\RequestCriteria;
use App\Tools\Taoke\Commission;
use App\Validators\Member\MemberValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Member\MemberRepository;


/**
 * Class MemberRepositoryEloquent.
 */
class MemberRepositoryEloquent extends BaseRepository implements MemberRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'status',
        'inviter_id',
        'member_id',
        'phone'
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Member::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return MemberValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
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

    /**
     * 团队报表
     * @return array|mixed
     */
    public function getTeamCharts()
    {
        $date_type = request('date_type','month');

        $member = getMember();
        $commission = new Commission();
        // 直属
        $query = db('members')->where('inviter_id', $member->id);
        $query = $commission->getQuery($query,$date_type);
        $directly = $query->count();
        //直属成员下级
        $query2 = db('members')->whereIn('inviter_id',function ($query3) use ($member) {
            $query3->select('id')
                ->from('members')
                ->where('inviter_id', $member->id);
        });
        $query2 = $commission->getQuery($query2,$date_type);
        $subordinate = $query2->count();
        return [
            'totalNum' => $directly + $subordinate,
            'directly' => $directly,
            'subordinate' => $subordinate,
        ];
    }

    /**
     * 获取三级粉丝
     * @param int $level
     * @return mixed
     */
    public function getFrineds($level = 1)
    {
        $inviterId = request ('inviter_id') ? request ('inviter_id') : getMemberId ();
        //一级粉丝
        if ($level == 1) {
            return Member::where('inviter_id', $inviterId)
                ->orderBy('id', 'desc')
                ->paginate(20);
        }
        //二级粉丝
        if ($level == 2) {
            return Member::whereIn('inviter_id', function ($query) use ($inviterId) {
                $query->select('id')
                    ->from('members')
                    ->where('inviter_id', $inviterId);
            })->orderBy('id', 'desc')
                ->paginate(20);
        }
        //三级粉丝
        if ($level == 3) {
            return Member::whereIn('inviter_id', function ($query) use ($inviterId) {
                $query->select('id')
                    ->from('members')
                    ->whereIn('inviter_id', function ($query2) use ($inviterId) {
                        $query2->select('id')
                            ->from('members')
                            ->where('inviter_id', $inviterId);
                    });
            })->orderBy('id', 'desc')
                ->paginate(20);
        }
    }

    /**
     * 会员升级
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function promotionLevel()
    {
        $member = getMember();//获取用户信息
        $level = $member->level->level;
        //判断是否有可升级等级
        $levels = Level::where('status',1)
            ->where('level','>',$level)
            ->where('credit','<',$member->credit3)
            ->orderBy('level','desc')
            ->first();
        if($levels){
            db('members')
                ->where('id',$member->id)
                ->update(['level1'=>$levels['id']]);
            return json(1001,'升级成功');
        }
        return json(4001,'升级条件不满足,请努力赚取积分');
    }
}
