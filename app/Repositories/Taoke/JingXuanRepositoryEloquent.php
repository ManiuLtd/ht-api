<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\JingXuan;
use App\Tools\Taoke\Taobao;
use App\Validators\Taoke\JingxuanDpValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\JingXuanRepository;
use Ixudra\Curl\Facades\Curl;

/**
 * Class JingXuanRepositoryEloquent.
 */
class JingXuanRepositoryEloquent extends BaseRepository implements JingXuanRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return JingXuan::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return JingxuanDpValidator::class;
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function TaoCommand()
    {
        $model = $this->paginate(request('limit',10));
        $member = getMember();
        $pidModel = db('tbk_pids')
            ->where([
                'user_id' => getUserId(),
                'member_id' => getMemberId(),
            ])
            ->orderByRaw('RAND()')
            ->first();
        if ($pidModel) {
            $pid = $pidModel->taobao;
        }else{
            throw new \Exception('PID不正确');
        }
        foreach ($model['data'] as $k => $v){
            $tool = new Taobao();
            $command = $tool->link([
                'pid'    => $pid,
                'itemid' => $v['itemid']
            ]);
            if (strpos($v['comment1'],'$淘口令$')){
                $model['data'][$k]['comment1'] = str_replace('$淘口令$',$command,$v['comment1']);
            }
        }

        return $model;
    }
}
