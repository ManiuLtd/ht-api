<?php

namespace App\Tools\Taoke;

interface TBKInterface
{
    /**
     * 领券地址
     * @return mixed
     */
    public function getCouponUrl();

    /**
     * 超级搜索.
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
     * @param array $array
     * @return mixed
     */
    public function getOrders(array $array = []);

    /**
     * 爬虫.
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
     * @return mixed
     */
    public function super_category();


}
