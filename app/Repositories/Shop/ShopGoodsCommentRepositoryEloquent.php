<?php

namespace App\Repositories\Shop;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\ShopGoodsCommentRepository;
use App\Models\Shop\ShopGoodsComment;
use App\Validators\Shop\ShopGoodsCommentValidator;

/**
 * Class ShopGoodsCommentRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ShopGoodsCommentRepositoryEloquent extends BaseRepository implements ShopGoodsCommentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ShopGoodsComment::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return ShopGoodsCommentValidator::class;
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
