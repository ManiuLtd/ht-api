<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OrderTypeCriteria.
 */
class OrderTypeCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository.
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        //根据type判断  1直推订单 2下下级订单 3团队订单 4补贴订单
        $type = request('type') ?? 1;
        //判断类型是否正确
        if(!in_array($type,[1,2,3,4])){
            return json(4001,'订单类型错误');
        }
        $member = getMember();
        //直推订单
        if($type == 1){
            $where['member_id'] = $member->id;
            $model = $model->where($where);
        }
        //下级订单
        if($type == 2){
            $model = $model->whereIn('member_id', function ($query) use ($member) {
                $query->select('id')
                    ->from('members')
                    ->where([
                        'status' => 1,
                        'inviter_id' => $member->id
                    ]);
            });
        }
        //团队订单
        if($type == 3){
            if ($member->isagent != 1) {
                return json(4001,'只有团长才可以查看团队订单');
            }
            $where['group_id'] = $member->group_id;
            $model = $model->where($where);
        }
        //补贴订单
        if($type == 4){
            if ($member->isagent != 1) {
                return json(4001,'只有团长才可以查看团队订单');
            }
            $model = $model->where($where)
                ->whereIn('group_id', function ($query) use ($member) {
                    $query->select('group_id')
                        ->from('members')
                        ->where([
                            'isagent' => 1,
                            'inviter_id' => $member->id
                        ]);
                });
        }
        return $model;
    }
}
