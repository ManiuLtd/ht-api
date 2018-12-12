<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Repositories\Interfaces\Taoke\GuessRepository;

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
//        $zhuanti = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', []);
        
    }
}
