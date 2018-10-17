<?php

namespace App\Repositories\Member;

use App\Models\Member\Member;
use App\Criteria\RequestCriteria;
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
        'nickname' => 'like',
        'status',
        'inviter_id',
        'member_id',
        'alipay',
        'realname',
        'phone',
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

//    public function getMemberChart()
//    {
//        //后端可根据日志获取会员近一周、半月、一月的增长记录
//    }

    /**
     * 获取三级粉丝.
     * @param int $level
     * @return mixed
     */
    public function getFrineds($level = 1)
    {
        $inviterId = request('inviter_id') ? request('inviter_id') : getMemberId();
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
}
