<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/6
 * Time: 20:34
 */

interface TBKInterface
{

    /**
     * 获取优惠券地址
     * @param array $array
     * @return mixed
     */
    public function getCouponUrl(array $array);


    /**
     * 获取详情
     * @param array $array
     * @return mixed
     */
    public function getDetail(array $array);

    /**
     * @param array $array
     * @return mixed
     */
    public function search(array $array);

    /**
     * 获取订单
     * @param array $array
     * @return mixed
     */
    public function getOrders(array $array = []);

    /**
     * 自动绑定订单
     * @param array $array
     * @return mixed
     */
    public function autoBindOrder(array $array = []);

    /**
     * 手动提交订单
     * @param array $array
     * @return mixed
     */
    public function submitOrder(array $array);

    /**
     * 爬虫
     * @param array $array
     * @return mixed
     */
    public function spider(array $array = []);

}