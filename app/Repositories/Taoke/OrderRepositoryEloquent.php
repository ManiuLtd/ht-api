<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Order;
use App\Criteria\RequestCriteria;
use App\Validators\Taoke\OrderValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\OrderRepository;

/**
 * Class OrderRepositoryEloquent.
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return OrderValidator::class;
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
     * 获取订单
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function orderList()
    {
        //订单类型 1淘宝 2京东 3拼多多
        $type = request('type');
        if ($type && !in_array($type, [1, 2, 3])) {
            return json(4002,'订单类型错误');
        }
        //订单状态 1已失效 2已付款 3待返利 4已返利 5已退货
        $status = request('status');
        if ($status && !in_array($status, [1, 2, 3, 4, 5])) {
            return json(4003,'订单状态错误');
        }
//        $member = auth_api();
        //查询条件
        $where = [];
        if ($type) {
            $where['type'] = $type;
        }
        if ($status) {
            $where['status'] = $status;
        }
        $query = $this->model->with('member');
        $where['member_id'] = 1;
        $query = $query->where($where);
        if (request('date')) {
            $query = $query->whereDate('created_at', request('date'));
        }
        //查询
        $model = $query->orderBy('id', 'desc')
            ->paginate(10);
        //验证结果
        if (!$model) {
            return json(4001,'没获取到订单数据');
        }
        return json(1001,'订单获取成功',$model);
    }
}
