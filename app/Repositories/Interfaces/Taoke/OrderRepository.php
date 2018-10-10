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
    public function getOrderCharts();
    public function submitOrder();

}
