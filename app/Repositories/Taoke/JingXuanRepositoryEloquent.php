<?php

namespace App\Repositories\Taoke;

use App\Models\User\Group;
use App\Models\User\Level;
use App\Models\User\User;
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
    public function getList()
    {
        $model = $this->paginate(request('limit',10));
        foreach ($model['data'] as &$v){
            $tool = new Taobao();
            try{
                //获取优惠券信息
                $coupon = $tool->getCouponUrl([
                    'item_id' => $v['itemid']
                ]);
                if (strpos($v['comment1'],'$淘口令$')){
                    $kouling = $tool->taokouling ([
                        'coupon_click_url' => $coupon->coupon_click_url,
                        'pict_url' => $v['pic_url'][0],
                        'title' => $v['title']
                    ]);
                    $v['comment1'] = str_replace('$淘口令$',$kouling,$v['comment1']);
                }
            }catch (\Exception $e){

            }

        }

        return $model;
    }
}
