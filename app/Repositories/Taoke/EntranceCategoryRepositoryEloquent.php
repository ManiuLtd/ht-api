<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Entrance;
use App\Models\Taoke\EntranceCategory;
use App\Validators\Taoke\EntranceCategoryValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\EntranceCategoryRepository;

/**
 * Class EntranceRepositoryEloquent.
 */
class EntranceCategoryRepositoryEloquent extends BaseRepository implements EntranceCategoryRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title' => 'like',
        'status',
        'parent_id',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return EntranceCategory::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return EntranceCategoryValidator::class;
    }

    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * 超级入口分类
     * @return mixed
     */
    public function list()
    {
        $model = $this->findWhere(['parent_id' => 0]);
        $data = $model['data'];
        foreach ($data as $key => $v){
            $last = $this->findWhere(['parent_id' => $v['id']]);
            $arr = [];
            if (count($last['data']) > 0){
                $arr = $last['data'];
                foreach ($arr as $k => $val){
                    $entrance = Entrance::query()->where('category_id',$val['id'])->get()->toArray();
                    $arr[$k]['entrance'] = $entrance;
                }
            }else{
                $entrance = Entrance::query()->where('category_id',$v['id'])->get()->toArray();
                $arr[]['entrance'] = $entrance;
            }
            $data[$key]['last'] = $arr;
        }
        return $data;
    }
}
