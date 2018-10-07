<?php

namespace App\Repositories\Image;

use App\Models\Image\Banner;
use App\Criteria\RequestCriteria;
use App\Validators\Image\BannerValidator;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Image\BannerRepository;

/**
 * Class BannerRepositoryEloquent.
 */
class BannerRepositoryEloquent extends BaseRepository implements BannerRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tag' => 'like',
        'status',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Banner::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return BannerValidator::class;
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

    public function getImage(Request $request)
    {
        $where = [];
        $tag = $request->tag;
        if($tag){
            $where['tag'] = $tag;
        }
        $image = db('banners')
            ->where($where)
            ->orderBy('sort','desc')
            ->get();
        return json('1001','图标列表获取成功',$image);
    }
}
