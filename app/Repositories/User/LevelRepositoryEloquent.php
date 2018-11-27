<?php

namespace App\Repositories\User;

use App\Models\User\Level;
use App\Criteria\RequestCriteria;
use App\Models\User\User;
use App\Validators\User\LevelValidator;
use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\User\LevelRepository;

/**
 * Class LevelRepositoryEloquent.
 */
class LevelRepositoryEloquent extends BaseRepository implements LevelRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'status',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Level::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return LevelValidator::class;
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
     * 付费升级
     * @throws \Exception
     */
    public function payment()
    {
        $user = getUser();
        $level_id = request('level_id');
        $type = request('type');//1月2季3年4永久
        if (!in_array($type,[1,2,3,4])){
            throw new \Exception('传参错误');
        }else{
            $price = 'price'.$type;
        }
        $money = request('money');
        $level = Level::query()->find($level_id);
        if (!$level){
            throw new \Exception('等级信息错误');
        }
        $level_user = Level::query()->find($user->level_id);
        if ($level_user->level > $level->level){
            throw new \Exception('当前等级大于所要升级的等级');
        }
            if ($level[$price] == $money){
            if ($type == 1){
                $time = Carbon::now()->addDays(30);
            }elseif ($type == 2){
                $time = Carbon::now()->addMonths(3);
            }elseif ($type == 3){
                $time = Carbon::now()->addYears(1);
            }else{
                $time = null;
            }
            User::query()->where('id',$user->id)->update([
                'level_id'     => $level->id,
                'expired_time' => $time
            ]);
        }else{
            throw new \Exception('升级失败');
        }
    }
}
