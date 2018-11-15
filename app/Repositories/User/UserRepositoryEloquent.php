<?php

namespace App\Repositories\User;

use App\Models\User\User;
use App\Criteria\RequestCriteria;
use App\Validators\User\UserValidator;
use App\Repositories\Interfaces\User\UserRepository;
use Hashids\Hashids;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepositoryEloquent.
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nickname' => 'like',
        'status',
        'inviter_id',

        'alipay' => 'like',
        'realname' => 'like',
        'phone' => 'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return UserValidator::class;
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

    public function getUserChart()
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
        $inviterId = request('inviter_id') ? request('inviter_id') : getUserId();
        //一级粉丝
        if ($level == 1) {
            return User::where('inviter_id', $inviterId)
                ->orderBy('id', 'desc')
                ->paginate(20);
        }
        //二级粉丝
        if ($level == 2) {
            return User::whereIn('inviter_id', function ($query) use ($inviterId) {
                $query->select('id')
                    ->from('users')
                    ->where('inviter_id', $inviterId);
            })->orderBy('id', 'desc')
                ->paginate(20);
        }
        //三级粉丝
        if ($level == 3) {
            return User::whereIn('inviter_id', function ($query) use ($inviterId) {
                $query->select('id')
                    ->from('users')
                    ->whereIn('inviter_id', function ($query2) use ($inviterId) {
                        $query2->select('id')
                            ->from('users')
                            ->where('inviter_id', $inviterId);
                    });
            })->orderBy('id', 'desc')
                ->paginate(20);
        }
    }

    /**
     * 绑定手机号.
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function bindMobile()
    {
        $user = getUser();
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
            return json(4001, $validator->errors()->first());
        }

        //验证手机号
        if (! preg_match("/^1[3456789]{1}\d{9}$/", $phone)) {
            return json(4001, '手机号格式不正确');
        }

        //验证手机号是否被占用
        if (db('users')->where([
            ['id', '<>', $user->id],
            'phone' => $phone,
        ])->first()) {
            return json(4001, '手机号已被其他用户绑定');
        }

        //验证短信是否过期
        if (! checkSms($phone, request('code'))) {
            return json(4001, '验证码不存在或者已过期');
        }

        //验证手机号是否存在
        try {
            //查询用户
            $userModel = db('users')->find($user->id);
            if (! $userModel) {
                return json(4001, '用户不存在');
            }
            $userModel->update([
//                'tag' => Hashids::encode($userModel->id),
                'phone' => $phone,
                'password' => bcrypt(request('password')),
            ]);
            //增加积分
            $data = new User();
            $data->increment('credit2', 20, ['remark'=>131313, 'operaterId'=>1]);

            return json(1001, '手机号绑定成功');
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 绑定邀请人.
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function bindInviter()
    {
        $user = getUser();

        $number = request('number');

        $decodeID = Hashids::decode($number);
        if (! isset($decodeID[0])) {
            return json(4001, '邀请码不存在');
        }
        $inviterId = $decodeID[0];

        //禁止绑定已被绑定过的用户
        if ($user->inviter_id != null) {
            return json(4001, '用户已被绑定');
        }

        //验证邀请码是否存在
        $inviterModel = db('users')->find($inviterId);

        if (! $inviterModel) {
            return json(4001, '邀请码不存在');
        }

        if ($inviterModel->id == $user->id) {
            return json(4001, '禁止绑定自己');
        }
        if (! $inviterModel->user_id) {
            return json(4001, '邀请人还没归属客户');
        }
        if (! $inviterModel->group_id) {
            return json(4001, '邀请人还没归属组');
        }

        $userModel = User::find($inviterModel->user_id);

        if ($userModel->sms < 0) {
            return json(4001, '短信余额不足');
        }

        //绑定上级 并结算短信
        try {
            //查询用户
            $user->update([
//                'tag' => Hashids::encode($user->id),
                'inviter_id' => $user->id == $inviterModel->id ? null : $inviterModel->id,
                'group_id' => $inviterModel->group_id,
                'user_id' => $inviterModel->user_id,
            ]);
            //结算短信
            if ($user->phone != null) {
                //扣除短信余额
                $count = db('sms')
                    ->where('phone', $user->phone)
                    ->whereNull('user_id')
                    ->count();
                $userModel->decrement('sms', $count);
                //设置短信所属用户
                db('sms')
                    ->where('phone', $user->phone)
                    ->whereNull('user_id')
                    ->update([
                        'user_id' => $inviterModel->user_id,
                    ]);
            }

            return json(1001, '邀请码绑定成功');
        } catch (\Exception $e) {
            return json(5001, '邀请码绑定失败'.$e->getMessage());
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register()
    {
        $phone = request('phone');
        //验证字段
        $rules = [
            'phone' => 'required',
            'code' => 'required',
            'password' => 'required|min:6',
        ];
        $messages = [
            'code.required' => '验证码为必填项',
            'password.min' => '密码最低6位数',
            'password.required' => '密码为必填项',
        ];

        $validator = \Validator::make(request()->all(), $rules, $messages);
        //字段验证失败
        if ($validator->fails()) {
            return json(4061, $validator->errors()->first());
        }
        //验证手机号
        if (! preg_match("/^1[3456789]{1}\d{9}$/", $phone)) {
            return json(4001, '手机号格式不正确');
        }

        //验证手机号是否被占用
        if (db('users')->where([
            'phone' => $phone,
        ])->first()) {
            return json(4001, '手机号已被其他用户绑定');
        }
        //验证短信是否过期
        if (! checkSms($phone, request('code'))) {
            return json(4001, '验证码不存在或者已过期');
        }
        //创建
        $user = $this->model->newQuery()->create([
            'phone' => $phone,
            'password' => Hash::make(request('password')),
        ]);
        //判断是否有邀请码
        if ($inviter_code = request('inviter_code')) {
            $this->bindinviterRegister($user);
        }
        $token = auth()->login($user);

        $hashids = new Hashids(config('hashids.SALT'), config('hashids.LENGTH'), config('hashids.ALPHABET'));
        return json(1001,'注册成功',[
            'tag' => $hashids->encode($user->id),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);

    }

    /**
     * @param $user
     * @return bool
     * @throws \Exception
     */
    protected function bindinviterRegister($user)
    {

        $inviter_code = request('inviter_code');
        $hashids = new Hashids(config('hashids.SALT'), config('hashids.LENGTH'), config('hashids.ALPHABET'));

        $decodeID = $hashids->decode($inviter_code);

        if (! isset($decodeID[0])) {
            db('users')->delete($user->id);
            throw new \Exception('邀请码不存在');
        }
        $inviterId = $decodeID[0];

        //验证邀请码是否存在
        $inviterModel = db('users')->find($inviterId);

        if (! $inviterModel) {
            db('users')->delete($user->id);
            throw new \Exception('邀请码不存在');
        }
        if (! $inviterModel->inviter_id) {
            db('users')->delete($user->id);
            throw new \Exception('邀请人还没归属客户');
        }
        if (! $inviterModel->group_id) {
            db('users')->delete($user->id);
            throw new \Exception('邀请人还没归属组');
        }
       $user->update([
           'inviter_id' => $inviterModel->id,
           'group_id' => $inviterModel->group_id,
       ]);
        return true;
    }
}
