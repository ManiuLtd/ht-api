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
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        //根据type判断  1直推订单 2下下级订单 3团队订单 4补贴订单
        $type = request('type') ?? 1;
        //判断类型是否正确
        if (! in_array($type, [1, 2, 3, 4])) {
            throw new \Exception('订单类型错误');
        }
        $member = getUser();
        //直推订单
        if ($type == 1) {
            return $model->where('user_id', $member->id);
        }
        //下级订单
        if ($type == 2) {
            return $model->whereIn('user_id', function ($query) use ($member) {
                $query->select('id')
                    ->from('members')
                    ->where([
                        'inviter_id' => $member->id,
                    ]);
            });
        }
        //团队订单
        if ($type == 3) {
            $group = db('groups')->find($member->group_id);
            if ($group->user_id != $member->id) {
                throw new \Exception('该用户无团队订单');
            }

            return $model->where('group_id', $member->group_id);
        }
        //补贴订单
        if ($type == 4) {
            $group = db('groups')->find($member->group_id);
            if ($group->user_id != $member->id) {
                throw new \Exception('该用户无补贴订单');
            }

            return $model->where('oldgroup_id', $member->group_id);
        }

        return $model;
    }
}
