<?php

namespace App\Repositories\Shop;

use App\Models\Shop\Goods;
use App\Criteria\RequestCriteria;
use Illuminate\Support\Facades\DB;
use App\Validators\Shop\GoodsValidator;
use Illuminate\Database\QueryException;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Shop\GoodsRepository;

/**
 * Class GoodsRepositoryEloquent.
 */
class GoodsRepositoryEloquent extends BaseRepository implements GoodsRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Goods::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return GoodsValidator::class;
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
     * 添加商品
     * @param array $attributes
     * @return mixed|void
     * @throws \Throwable
     * @see https://laravel-china.org/docs/laravel/5.6/database/1397#database-transactions
     */
    public function create(array $attributes)
    {
        try {
            //使用数据库事务，有一个创建失败，则回滚
            DB::transaction(function () use ($attributes) {
                //添加产品
                $goods = $this->model()::create($attributes);
                //添加所属分类
                $goods->categories()->attach(request('categories'));
                //添加属性
                if ($params = request('params')) {
                    foreach ($params as &$param) {
                        $param['goods_id'] = $goods->id;
                        $param['created_at'] = now();
                        $param['updated_at'] = now();
                    }
                    db('shop_goods_params')->insert($params);
                }
                //TODO 添加规格
            });
        } catch (QueryException $e) {
            throw new \InvalidArgumentException('参数错误，检查分类、规格或者属性参数是否正确');
        }
    }

    /**
     * 编辑商品
     * @param array $attributes
     * @param $id
     * @return mixed|void
     * @throws \Throwable
     */
    public function update(array $attributes, $id)
    {
        try {
            //使用数据库事务，有一个编辑失败，则回滚
            DB::transaction(function () use ($attributes, $id) {
                //当前商品模型
                $goods = $this->model()::find($id);
                //编辑商品
                $goods->update($attributes);
                //编辑所属分类
                $goods->categories()->sync(request('categories'));
                //编辑属性
                if ($params = request('params')) {
                    foreach ($params as &$param) {
                        $param['goods_id'] = $goods->id;
                        $param['created_at'] = now();
                        $param['updated_at'] = now();
                    }
                    db('shop_goods_params')->where('goods_id', $goods->id)->delete();
                    db('shop_goods_params')->insert($params);
                }
                //TODO 更新规格
            });
        } catch (QueryException $e) {
            throw new \InvalidArgumentException('参数错误，检查分类、规格或者属性参数是否正确');
        }
    }
}
