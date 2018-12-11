<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Tools\Taoke\TBKInterface;

/**
 * Class GuessController.
 */
class GuessController extends Controller
{
    /**
     * @var TBKInterface
     */
    protected $tbk;

    /**
     * GuessController constructor.
     * @param TBKInterface $tbk
     */
    public function __construct(TBKInterface $tbk)
    {
        $this->tbk = $tbk;
    }
    /**
     * 猜你喜欢.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
//        $zhuanti = $this->repository->paginate(request('limit', 10));
        try {
            $itemid = request('itemid');
            $data = $this->tbk->guessLike($itemid);

            return json(1001, '获取成功', $data);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
