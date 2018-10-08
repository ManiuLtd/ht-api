<?php

namespace App\Repositories\Member;

use App\Models\Member\Member;
use App\Criteria\RequestCriteria;
use App\Validators\Member\MemberValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Member\MemberRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
     * 获取三级粉丝.
     * @param $member
     * @param $level
     * @return mixed
     */
    public function getFriends($member, $level)
    {
        //一级粉丝
        if ($level == 1) {
            return Member::where('inviter_id', $member->id)
                ->orderBy('id', 'desc')
                ->paginate(20);
        }
        //二级粉丝
        if ($level == 2) {
            return Member::whereIn('inviter_id', function ($query) use ($member) {
                $query->select('id')
                    ->from('members')
                    ->where('inviter_id', $member->id);
            })->orderBy('id', 'desc')
                ->paginate(20);
        }
        //三级粉丝
        if ($level == 3) {
            return Member::whereIn('inviter_id', function ($query) use ($member) {
                $query->select('id')
                    ->from('members')
                    ->whereIn('inviter_id', function ($query2) use ($member) {
                        $query2->select('id')
                            ->from('members')
                            ->where('inviter_id', $member->id);
                    });
            })->orderBy('id', 'desc')
                ->paginate(20);
        }
    }

    /**
     * 好友列表  二级
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function friendsList($id)
    {
        $member_first =  Member::query()
            ->with(['level', 'inviter'])
            ->where('inviter_id', $id)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        if (count($member_first) > 0){
            $member_second = Member::query()
                ->whereIn('inviter_id', function ($query) use ($id) {
                    $query->select('id')
                        ->from('members')
                        ->where('inviter_id', $id);
                })
                ->orderBy('id', 'desc')
                ->with(['level', 'inviter'])
                ->get()
                ->toArray();
        }else{
            $member_second = [];
        }
        $friend_list = array_merge($member_first,$member_second);
        $currentPage =  LengthAwarePaginator::resolveCurrentPage() - 1;
        $collection = new Collection($friend_list);
        $perPage = 20;
        $currentPageSearchResults = $collection->slice($currentPage * $perPage, $perPage)->all();
        $paginatedSearchResults= new LengthAwarePaginator($currentPageSearchResults, count($friend_list), $perPage);
        $paginatedSearchResults = $paginatedSearchResults->setPath($_SERVER['HTTP_HOST'].'/api/member/friend_list');
        return json('1001','好友列表',$paginatedSearchResults);
    }
}
