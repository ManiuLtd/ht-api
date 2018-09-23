<?php

namespace App\Repositories\Shop;

use App\Models\Shop\GoodsTag;
use App\Criteria\RequestCriteria;
use App\Validators\Shop\GoodsTagValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\ShopGoodsTagRepository;

/**
 * Class GoodsTagRepositoryEloquent.
 */
class GoodsTagRepositoryEloquent extends BaseRepository implements ShopGoodsTagRepository
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
        return GoodsTag::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return GoodsTagValidator::class;
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
}
