<?php

namespace App\Repositories\Taoke;

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

    public function list()
    {
        $type = $this->all();
        return $this->getTree($type,'title','id','parent_id');

    }

    public function getTree($data,$field_name,$field_id='id',$field_pid='parent_id',$pid=0)
    {
        $arr = [];
        $data = $data['data'];
        if (count($data) > 0){
            foreach ($data as $k=>$v){
                if($v[$field_pid] == $pid){
                    $data[$k]["_".$field_name] = $data[$k][$field_name];
                    $arr[] = $data[$k];
                    foreach ($data as $m=>$n){
                        if($n[$field_pid] == $v[$field_id]){
                            $data[$m]["_".$field_name] = '├─ '.$data[$m][$field_name];
                            $arr[] = $data[$m];
                        }
                    }
                }
            }
        }



        return $arr;
    }
}
