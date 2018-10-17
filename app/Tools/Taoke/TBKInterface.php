<?php

namespace App\Tools\Taoke;

interface TBKInterface
{
    /**
     * 领券地址
     * @param array $array
     * @return mixed
     *
     */
    public function getCouponUrl(array $array = []);

    /**
     * 优惠券详情.
     * @return mixed
     */
    public function getDetail(array $array = []);

    /**
     * 搜索.
     * @return mixed
     */
    public function search(array $array = []);

    /**
     * 获取订单.
     * @return mixed
     */
    public function getOrders(array $array = []);

    /**
     * 自动绑定订单.
     * @return mixed
     */
    public function autoBindOrder(array $array = []);

    /**
     * 爬虫
     * @param array $params
     * @return mixed
     */
    public function spider(array $params = []);

    /**
     * 获取热搜词.
     * @return mixed
     */
    public function hotSearch(array $array = []);

    /**
     *  好货专场
     * @param array $array
     * @return mixed
     */
    public function HaohuoZC(array $array = []);

    /**
     * 精选单品
     * @param array $array
     * @return mixed
     */
    public function JingxuanDP(array $array = []);

    /**
     * 精选专题
     * @param array $array
     * @return mixed
     */
    public function JingxuanZT(array $array = []);

    /**
     * 快抢商品
     * @param array $array
     * @return mixed
     */
    public function KuaiqiangShop(array $array = []);


}
