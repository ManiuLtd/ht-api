<?php

namespace App\Repositories\Shop;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\ShopGoodsTagRepository;
use App\Models\Shop\ShopGoodsTag;
use App\Validators\Shop\ShopGoodsTagValidator;

/**
 * Class ShopGoodsTagRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ShopGoodsTagRepositoryEloquent extends BaseRepository implements ShopGoodsTagRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'status',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ShopGoodsTag::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return ShopGoodsTagValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
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
        return "Prettus\\Repository\\Presenter\\ModelFractalPresenter";
    }
}
