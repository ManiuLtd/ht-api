<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Order;
use App\Criteria\RequestCriteria;
use App\Tools\Taoke\Commission;
use App\Validators\Taoke\OrderValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\OrderRepository;

/**
 * Class OrderRepositoryEloquent.
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{
    /**
     * @var
     */
    protected $commission;

    /**
     * OrderRepositoryEloquent constructor.
     * @param Commission $commission
     */
    public function __construct(Commission $commission)
    {
        $this->commission = $commission;
    }
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'status',
        'created_at',
    ];

    /**
     * TODO
     * 订单数据报表
     * @return array|mixed
     */
    public function getOrderCharts()
    {
        $member = getMember();

        $date_type = request('date_type','month');
        //自推佣金
        $moneyData = $this->commission->selfPush($member->id, $date_type);

        //团队订佣金
        //下级佣金
        $subordinateData = $this->commission->subordinate($member->id, $date_type);
        //组长提成
        $moneyLeader = $this->commission->leader($member->id, $date_type) + $this->commission->old_leader($member->id, $date_type);
        $amount = $moneyLeader + $subordinateData + $moneyData['money'];

        // 是否是组长
        $orderNum = 0;
        $group = $member->group;
        if ($member->id == $group->member_id ?? 0) {
            $query = db('tbk_orders')->whereYear('created_at',now()->year);
            $query = $this->commission->getQuery($query, $date_type)
                ->whereRaw("status = 3 and (group_id = $group->id or oldgroup_id = $group->id )");
            $orderNum = $query->count();
        }
        return [
            'Independence' => $moneyData,
            'team' => [
               'money' => $amount,
               'orderNum' => $orderNum
            ],
        ];


    }

    /**
     * 提交订单
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitOrder()
    {
        $member = getMember();
        $ordernum = request('ordernum');
        if(is_numeric($ordernum) && strlen($ordernum) >= 16){
            $re = db('tbk_member_orders')
                ->where('ordernum',$ordernum)
                ->first();
            if($re){
                return json(4001,'订单号已提交');
            }
            $order = db('tbk_orders')->where([
                'ordernum'=>$ordernum
            ])->first();
            if($order){
               db('tbk_member_orders')->insert([
                    'user_id' => $member->user_id,
                    'member_id' => $member->id,
                    'ordernum' => $ordernum,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ]);
               return json(1001,'订单提交成功');
            }
        }
        return json(4001,'订单格式不对');
    }
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
}
