<?php

namespace App\Repositories\Interfaces\Taoke;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface OrderRepository.
 */
interface OrderRepository extends RepositoryInterface
{
    /**
     * @return mixed
     */
    public function getOrderChart();

    /**
     * 提交订单.
     * @return mixed
     */
    public function submitOrder();

    /**
     * 会员收入信息.
     * @return mixed
     */
    public function getMember();
}
