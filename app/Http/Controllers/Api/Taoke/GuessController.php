<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Tools\Taoke\Taobao;
use App\Http\Controllers\Controller;

/**
 * Class GuessController.
 */
class GuessController extends Controller
{
    /**
     * 猜你喜欢.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $itemid = request('itemid');

            $guess = new Taobao();
            $data = $guess->guessLike($itemid);

            return json(1001, '获取成功', $data);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
