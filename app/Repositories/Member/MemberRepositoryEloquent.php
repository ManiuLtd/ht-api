<?php

namespace App\Repositories\Member;

use App\Models\Member\Member;
use App\Criteria\RequestCriteria;
use App\Models\User\User;
use App\Validators\Member\MemberValidator;
use Hashids\Hashids;
use Illuminate\Support\Facades\Validator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Member\MemberRepository;
use function Sodium\increment;

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

    public function getMemberChart()
    {
        //TODO 后端可根据日志获取会员近一周、半月、一月的增长记录
    }

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

    /**
     * 绑定手机号
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function bindMobile()
    {
        $member = getMember();
        $phone = request('phone');
        //验证字段
        $rules = ['password' => 'required|min:6'];
        $messages = [
            'password.min' => '密码最低6位数',
            'password.required' => '密码为必填项',
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        //字段验证失败
        if ($validator->fails()) {
            return json(4001,$validator->errors()->first());
        }

        //验证手机号
        if (!preg_match("/^1[3456789]{1}\d{9}$/", $phone)) {
            return json(4001,'手机号格式不正确');
        }

        //验证手机号是否被占用
        if (db('members')->where([
            ['id', '<>', $member->id],
            'phone' => $phone,
        ])->first()) {
            return json(4001,'手机号已被其他用户绑定');
        }

        //验证短信是否过期
        if (!checkSms($phone,request('code'))) {
            return json(4001,'验证码不存在或者已过期');
        }

        //验证手机号是否存在
        try {
            //查询用户
            $memberModel = db('members')->find($member->id);
            if (!$memberModel) {
                return json(4001,'用户不存在');
            }
            $memberModel->update([
//                'tag' => Hashids::encode($memberModel->id),
                'phone' => $phone,
                'password' => bcrypt(request('password'))
            ]);
            //增加积分
            $data = new Member();
            $data->increment('credit2',20,['remark'=>131313,'operaterId'=>1]);
            return json(1001,'手机号绑定成功');
        } catch (Exception $e) {
            return json(5001,$e->getMessage());
        }
    }

    /**
     * 绑定邀请人
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function bindInviter()
    {
        $member = getMember();

        $number = request('number');

        $decodeID = Hashids::decode($number);
        if (!isset($decodeID[0])) {
            return json(4001,'邀请码不存在');
        }
        $inviterId = $decodeID[0];

        //禁止绑定已被绑定过的用户
        if ($member->inviter_id != null) {
            return json(4001,'用户已被绑定');
        }

        //验证邀请码是否存在
        $inviterModel = db('members')->find($inviterId);

        if (!$inviterModel) {
            return json(4001,'邀请码不存在');
        }

        if ($inviterModel->id == $member->id) {
            return json(4001,'禁止绑定自己');
        }
        if (!$inviterModel->user_id) {
            return json(4001,'邀请人还没归属客户');
        }
        if (!$inviterModel->group_id) {
            return json(4001,'邀请人还没归属组');
        }

        $userModel = User::find($inviterModel->user_id);

        if ($userModel->sms < 0) {
            return json(4001,'短信余额不足');
        }

        //绑定上级 并结算短信
        try {
            //查询用户
            $member->update([
//                'tag' => Hashids::encode($member->id),
                'inviter_id' => $member->id == $inviterModel->id ? null : $inviterModel->id,
                'group_id' => $inviterModel->group_id,
                'user_id' => $inviterModel->user_id,
            ]);
            //结算短信
            if ($member->phone != null) {
                //扣除短信余额
                $count = db('sms')
                    ->where('phone', $member->phone)
                    ->whereNull('user_id')
                    ->count();
                $userModel->decrement('sms', $count);
                //设置短信所属用户
                db('sms')
                    ->where('phone', $member->phone)
                    ->whereNull('user_id')
                    ->update([
                        'user_id' => $inviterModel->user_id
                    ]);
            }
            return json(1001,'邀请码绑定成功');
        } catch (Exception $e) {
            return json(5001,'邀请码绑定失败'.$e->getMessage());
        }

    }
}
