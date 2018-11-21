<?php

namespace App\Repositories\System;

use App\Models\System\Setting;
use App\Validators\System\SettingValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\System\SettingRepository;

/**
 * Class SettingRepositoryEloquent.
 */
class SettingRepositoryEloquent extends BaseRepository implements SettingRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Setting::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return SettingValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
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
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * 成长值途径列表.
     * @return array
     */
    public function upgrade()
    {
        $setting = \setting (1);
        $data = [];
        //订单
        $path = $setting->credit_order;

        $order_commission1 = [
            'title' => '直属订单',
            'describe' => '新增直属订单，奖励成长值',
            'credit' => $path->order_commission1_credit3
        ];

        $order_commission2 = [
            'title' => '下级订单',
            'describe' => '新增下级订单，奖励成长值',
            'credit' => $path->order_commission2_credit3
        ];


        $order_group1 = [
            'title' => '团队订单',
            'describe' => '新增团队订单，奖励成长值',
            'credit' => $path->order_group1_credit3
        ];


        $order_group2 = [
            'title' => '补贴订单',
            'describe' => '下级团队新增订单，奖励成长值',
            'credit' => $path->order_group2_credit3
        ];

        $friend_commission1 = [
            'title' => '新增粉丝',
            'describe' => '新增粉丝，奖励成长值',
            'credit' => $path->friend_commission1_credit3
        ];


        $friend_commission2 = [
            'title' => '新增下级粉丝',
            'describe' => '新增下级粉丝，奖励成长值',
            'credit' => $path->friend_commission2_credit3
        ];


        $friend_group1_credit3 = [
            'title' => '团队新增粉丝',
            'describe' => '团队新增粉丝，奖励成长值',
            'credit' => $path->friend_group1_credit3
        ];

        $friend_group2_credit3 = [
            'title' => '补贴团队新增粉丝',
            'describe' => '下级团队新增粉丝，奖励成长值',
            'credit' => $path->friend_group2_credit3
        ];

        array_merge ($order_commission1, $order_commission2, $order_group1, $order_group2, $friend_commission1, $friend_commission2, $friend_group1_credit3, $friend_group2_credit3);
        return $data;
    }
}
