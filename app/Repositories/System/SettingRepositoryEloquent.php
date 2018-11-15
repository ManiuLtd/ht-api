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
     * 成长值途径列表
     * @return array
     */
    public function upgrade()
    {
        $setting = \setting(1);
        $data = array();
        //订单
        $path = json_decode($setting->credit_order);
        $data['order']['title'] = '新增订单奖';
        $data['order']['describe'] = '每天新增一个订单,成长值增加'.$path->order_commission1_credit3;
        $data['order']['credit'] = $path->order_commission1_credit3;

        $data['order_commission2']['title'] = '新增订单推荐奖';
        $data['order_commission2']['describe'] = '每天新增一个订单,上级成长值增加'.$path->order_commission2_credit3;
        $data['order_commission2']['credit'] = $path->order_commission2_credit3;

        $data['order_group1']['title'] = '新增订单团队奖';
        $data['order_group1']['describe'] = '每天新增一个订单,组长成长值增加'.$path->order_group1_credit3;
        $data['order_group1']['credit'] = $path->order_group1_credit3;

        $data['order_group2']['title'] = '新增订单旧团队奖';
        $data['order_group2']['describe'] = '每天新增一个订单,旧组长成长值增加'.$path->order_group2_credit3;
        $data['order_group2']['credit'] = $path->order_group2_credit3;
        //粉丝
        $path = json_decode($setting->credit_friend);
        $data['friend']['title'] = '新增粉丝奖';
        $data['friend']['describe'] = '每天新增一个粉丝,成长值增加'.$path->friend_commission1_credit3;
        $data['friend']['credit'] = $path->friend_commission1_credit3;

        $data['friend_commission2']['title'] = '新增粉丝推荐奖';
        $data['friend_commission2']['describe'] = '每天新增一个粉丝,上级成长值增加'.$path->friend_commission2_credit3;
        $data['friend_commission2']['credit'] = $path->friend_commission2_credit3;

        $data['friend_group1']['title'] = '新增粉丝团队奖';
        $data['friend_group1']['describe'] = '每天新增一个粉丝,组长成长值增加'.$path->friend_group1_credit3;
        $data['friend_group1']['credit'] = $path->friend_group1_credit3;

        $data['friend_group2']['title'] = '新增粉丝旧团队奖';
        $data['friend_group2']['describe'] = '每天新增一个粉丝,旧组长成长值增加'.$path->friend_group2_credit3;
        $data['friend_group2']['credit'] = $path->friend_group2_credit3;
        //绑定电话，微信
        $path = json_decode($setting->credit);
        $data['phone']['title'] = '绑定手机';
        $data['phone']['describe'] = '绑定手机成长值增加'.$path->phone;
        $data['phone']['credit'] = $path->phone;

        $data['wechat']['title'] = '绑定微信';
        $data['wechat']['describe'] = '绑定微信成长值增加'.$path->wechat;
        $data['wechat']['credit'] = $path->wechat;

        $data['alipay']['title'] = '绑定支付宝';
        $data['alipay']['describe'] = '绑定支付宝成长值增加'.$path->alipay;
        $data['alipay']['credit'] = $path->alipay;
        return $data;
    }
}
