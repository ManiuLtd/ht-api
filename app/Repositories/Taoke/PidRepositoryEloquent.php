<?php

namespace App\Repositories\Taoke;

use Carbon\Carbon;
use App\Models\Taoke\Pid;
use App\Criteria\RequestCriteria;
use App\Validators\Taoke\PidValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\PidRepository;

/**
 * Class CategoriesRepositoryEloquent.
 */
class PidRepositoryEloquent extends BaseRepository implements PidRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'status',
        'taobao' => 'like',
        'jingdong' => 'like',
        'pinduoduo' => 'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Pid::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return PidValidator::class;
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
     * @param array $attributes
     * @return bool|mixed
     */
    public function create(array $attributes)
    {
        $pids = preg_split('/\s+/', $attributes['taobao']);
        if (count($pids) > 0) {
            foreach ($pids as $pid) {
                //禁止重复
                $isExist = db('tbk_pids')->where('taobao', $pid)->first();
                if (! $isExist) {
                    db('tbk_pids')->insert([
                        'user_id'    => getUserId(),
                        'taobao'     => trim($pid),
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }
            }

            return true;
        }

        return false;
    }
}
