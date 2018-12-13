<?php

namespace App\Repositories\User;

use App\Events\Upgrade;
use App\Models\User\Group;
use App\Models\User\Level;
use Hashids\Hashids;
use App\Models\User\User;
use App\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Hash;
use App\Validators\User\UserValidator;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use PhpParser\Node\Stmt\DeclareDeclare;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Taoke\Pid;
use App\Tools\Taoke\JingDong;
use App\Tools\Taoke\PinDuoDuo;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\User\UserRepository;
use Prettus\Repository\Generators\ModelGenerator;

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
        'level_id',
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
        $this->pushCriteria (app (RequestCriteria::class));
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
     * @return mixed
     */
    public function getFrineds()
    {
        //直属还是推荐
        $level = request ('level', 1);
        //分页数
        $limit = request ('limit', 20);
        //上级邀请人
        $inviterId = request ('inviter_id') ? request ('inviter_id') : getUserId ();
        //手机号
        $phone = request ('phone');
        //查询条件
        $query = User::query ();
        //一级粉丝
        if ($level == 1) {
            $query = $query->where ('inviter_id', $inviterId)->withCount ('friends');
        }
        //二级粉丝
        if ($level == 2) {
            $query = $query->whereIn ('inviter_id', function ($query) use ($inviterId) {
                $query->select ('id')
                    ->from ('users')
                    ->where ('inviter_id', $inviterId);
            })->withCount ('friends');
        }
        //三级粉丝
        if ($level == 3) {
            $query = $query->whereIn ('inviter_id', function ($query) use ($inviterId) {
                $query->select ('id')
                    ->from ('users')
                    ->whereIn ('inviter_id', function ($query) use ($inviterId) {
                        $query->select ('id')
                            ->from ('users')
                            ->where ('inviter_id', $inviterId);
                    });
            })->withCount ('friends');
        }

        //团队粉丝
        if ($level == 4) {
            $user = getUser ();
            $group = db ('groups')->where ('user_id',$user->id)->first ();
            if(!$group){
                return null;
            }
            $query = $query->where ([
                'group_id' => $group->id
            ])->withCount ('friends');
        }
        //根据手机号查询
        if ($phone) {
            $query = $query->where ('phone', $phone);
        }

        $data = (object) $query->orderBy ('id', 'desc')->paginate ($limit)->toArray();

        return [
            'data' => $data->data,
            'meta' => [
                'current_page' => $data->current_page,
                'from' => $data->from,
                'last_page' => $data->last_page,
                'per_page' => $data->per_page,
                'to' => $data->to,
                'total' => $data->total,
            ]
        ];
    }

    /**
     * 绑定手机号
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function bindMobile()
    {
        $user = getUser ();
        $phone = request ('phone');
        //验证字段
        $rules = ['password' => 'required|min:6'];
        $messages = [
            'password.min' => '密码最低6位数',
            'password.required' => '密码为必填项',
        ];
        $validator = Validator::make (request ()->all (), $rules, $messages);
        //字段验证失败
        if ($validator->fails ()) {
            throw  new \Exception($validator->errors ()->first ());
        }

        //验证手机号
        if (!preg_match ("/^1[3456789]{1}\d{9}$/", $phone)) {
            throw  new \Exception('手机号格式不正确');
        }

        //验证手机号是否被占用
        if (db ('users')->where ([
            ['id', '<>', $user->id],
            'phone' => $phone,
        ])->first ()) {
            throw  new \Exception('手机号已被其他用户绑定');

        }

        //验证短信是否过期
        if (!checkSms ($phone, request ('code'))) {
            throw  new \Exception('验证码不存在或者已过期');
        }

        User::query ()->where ('id', $user->id)->update ([
            'phone' => $phone,
            'password' => bcrypt (request ('password')),
        ]);

        return json ('1001', '绑定成功');
    }

    /**
     * 绑定邀请人
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function bindInviter()
    {
        $user = getUser ();

        $number = request ('number');
        $hashids = new Hashids(config ('hashids.SALT'), config ('hashids.LENGTH'), config ('hashids.ALPHABET'));
        $decodeID = $hashids->decode ($number);
        if (!isset($decodeID[0])) {
            throw  new \Exception('邀请码错误');

        }
        $inviterId = $decodeID[0];

        //禁止绑定已被绑定过的用户
        if ($user->inviter_id != null) {
            throw  new \Exception('用户已被绑定');
        }

        //验证邀请码是否存在
        $inviterModel = db ('users')->find ($inviterId);

        if (!$inviterModel) {
            throw  new \Exception('邀请人不存在');
        }

        if ($inviterModel->id == $user->id) {
            throw  new \Exception('禁止绑定自己');

        }


        //查询用户
        $user->update ([
            'inviter_id' => $inviterModel->id,
            'group_id' => $inviterModel->group_id,
        ]);


        return json ('1001', '绑定成功');
    }

    /**
     * 根据邀请码查看用户信息.
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function getInviter()
    {
        $tag = request ('tag');
        if (!$tag) {
            throw new \Exception('缺少参数');
        }
        $hashids = new Hashids(config ('hashids.SALT'), config ('hashids.LENGTH'), config ('hashids.ALPHABET'));
        $decode = $hashids->decode ($tag);
        $user = User::query ()->find ($decode[0]);
        if (!$user) {
            throw new \Exception('该用户不存在');
        }

        return $user;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register()
    {
        $phone = request ('phone');
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

        $validator = \Validator::make (request ()->all (), $rules, $messages);
        //字段验证失败
        if ($validator->fails ()) {
            return json (4061, $validator->errors ()->first ());
        }
        //验证手机号
        if (!preg_match ("/^1[3456789]{1}\d{9}$/", $phone)) {
            return json (4001, '手机号格式不正确');
        }

        //验证手机号是否被占用
        if (db ('users')->where ([
            'phone' => $phone,
        ])->first ()) {
            return json (4001, '手机号已被其他用户绑定');
        }
        //验证短信是否过期
        if (!checkSms ($phone, request ('code'))) {
            return json (4001, '验证码不存在或者已过期');
        }
        $level = Level::query ()->where ('default', 1)->first ();

        //创建
        $user = $this->model->newQuery ()->create ([
            'phone' => $phone,
            'password' => Hash::make (request ('password')),
            'level_id' => $level->id,
        ]);
        //判断是否有邀请码
        if ($inviter_code = request ('inviter_code')) {
            $this->bindinviterRegister ($user);
        }
        $token = auth ()->login ($user);

        $hashids = new Hashids(config ('hashids.SALT'), config ('hashids.LENGTH'), config ('hashids.ALPHABET'));

        return json (1001, '注册成功', [
            'tag' => $hashids->encode ($user->id),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth ()->factory ()->getTTL () * 60,
            'user' => $user,
        ]);
    }

    /**
     * @param $user
     * @param null $inviter
     * @return bool
     * @throws \Exception
     */
    public function bindinviterRegister($user, $inviter = null)
    {
        $inviter_code = $inviter != null ? $inviter : request ('inviter_code');
        $hashids = new Hashids(config ('hashids.SALT'), config ('hashids.LENGTH'), config ('hashids.ALPHABET'));

        $decodeID = $hashids->decode ($inviter_code);

        if (!isset($decodeID[0])) {
            throw new \Exception('邀请码不存在');
        }
        $inviterId = $decodeID[0];

        //验证邀请码是否存在
        $inviterModel = db ('users')->find ($inviterId);

        if (!$inviterModel) {
            throw new \Exception('邀请人不存在');
        }

        if ($user->id == $inviterId){
            throw new \Exception('不能绑定自己');
        }

        $user->update ([
            'inviter_id' => $inviterModel->id,
            'group_id' => $inviterModel->group_id,
        ]);

        return true;
    }

    /**
     * 手动升级
     * @return mixed|void
     * @throws \Throwable
     */
    public function checkUpgrade()
    {
        $user = getUser();
        $level_id = request('level_id');

        $group = $user->group;
        //淘宝推广位是否存在
        $pid = Pid::query ()->whereNull('agent_id')->where('user_id',$group->user_id)->whereNotNull('taobao')->first ();
        if (!$pid) {
            throw new \Exception('升级失败，pid不够');
        }


        if (!$level_id) {
            throw new \Exception('level_id error');
        }
        $user_level = Level::query()->find($user->level_id);
        if (!$user_level){
            throw new \Exception('用户等级信息错误');
        }
        $level = db('user_levels')->find($level_id);

        if (! $level) {
            throw new \Exception('等级不存在');
        }
        if ($user_level->level >= $level->level) {
            throw new \Exception('用户等级已最高');
        }

        if ($user->credit3 < $level->credit) {
            throw new \Exception('成长值不够不能升级');
        }


        event(new Upgrade($user, $level));
    }

    /**
     * 绑定支付宝
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function bindAlipay()
    {
        $user = getUser();
        $phone = request ('phone');
        //验证字段
        $rules = [
            'phone' => 'required',
            'code' => 'required',
            'realname' => 'required',
            'alipay' => 'required',
        ];

        $validator = \Validator::make (request ()->all (), $rules);
        //字段验证失败
        if ($validator->fails ()) {
            return json (4061, $validator->errors ()->first ());
        }
        //验证手机号
        if (!preg_match ("/^1[3456789]{1}\d{9}$/", $phone)) {
            return json (4001, '手机号格式不正确');
        }
        if (!$user->phone) {
            throw new \Exception('请先绑定手机号');
        }
        if ($user->phone != $phone) {
            throw new \Exception('手机号与绑定的手机号不一致');
        }
        if (!checkSms($phone,request('code'))) {
            throw new \Exception('验证码不正确');
        }
        $user->update([
            'realname' => request('realname'),
            'alipay' => request('alipay'),
        ]);
        return json(1001,'支付宝绑定成功');
    }

    /**
     * 用户报表
     */
    public function chart()
    {
        $type = request('type');
        $query = DB::table('users');
        switch ($type){
            case 'today':
               $query = $query->whereDate('created_at',today()->toDateString())
               ->select(DB::raw ("DATE_FORMAT(created_at,'%Y-%m-%d %h') time"),DB::raw('count(id) as total_count'));
               break;
            case 'week':
                $query = $query->whereDate('created_at','>=',now()->addDay(-7)->toDateString())
                    ->select(DB::raw ("DATE_FORMAT(created_at,'%Y-%m-%d') time"),DB::raw('count(id) as total_count'));
                break;
            case 'month':
                $query = $query->whereMonth('created_at','>=',now ()->month)
                    ->select(DB::raw ("DATE_FORMAT(created_at,'%Y-%m-%d') time"),DB::raw('count(id) as total_count'));
                break;
            case 'year':
                $query = $query->whereYear('created_at','>=',now ()->year)
                    ->select(DB::raw ("DATE_FORMAT(created_at,'%Y-%m') time"),DB::raw('count(id) as total_count'));
                break;
            case 'custom':
                $query = $query->whereDate('created_at','>=', request('start_time'))
                    ->whereDate('created_at','<=', request('end_time'))
                    ->select(DB::raw ("DATE_FORMAT(created_at,'%Y-%m') time"),DB::raw('count(id) as total_count'));
                break;
        }
        return $query->groupBy('time')->get();
    }
}












