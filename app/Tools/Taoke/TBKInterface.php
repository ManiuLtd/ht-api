<?php

namespace App\Tools\Taoke;

interface TBKInterface
{
    /**
     * 领券地址
     * @return mixed
     *
     */
    public function getCouponUrl();

    /**
     * 超级搜索
     * @return mixed
     */
    public function getDetail();

    /**
     * 搜索.
     * @return mixed
     */
    public function search();

    /**
     * 获取订单.
     * @return mixed
     */
    public function getOrders();

    /**
     * 爬虫
     * @param array $params
     * @return mixed
     */
    public function spider(array $params);

    /**
     * 获取热搜词.
     * @return mixed
     */
    public function hotSearch();

    /**
     * 失效商品
     * @return mixed
     */
    public function deleteCoupon(array $params);

    /**
     * 精选专题
     * @return mixed
     */
    public function zhuanti();


     /**
     * 生成淘口令
     * @param array $array
     * @return mixed
     */
    public function taokouling(array $array = []);


    /**
     * 精选单品
     * @param array $array
     * @return mixed
     */
    public function danpin(array $array = []);

    /**
     * 快抢商品
     * @param array $array
     * @return mixed
     */
    public function KuaiqiangShop(array $array = []);

    /**
     * 快抢商品
     * @param array $array
     * @return mixed
     */
    public function updateCoupon(array $array = []);

}
