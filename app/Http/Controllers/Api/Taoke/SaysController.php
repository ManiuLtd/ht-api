<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\SaysRepository;
use App\Tools\Taoke\Taobao;
use App\Tools\Taoke\TBKInterface;

/**
 * Class SaysController
 * @package App\Http\Controllers\Api\Taoke
 */
class SaysController extends Controller
{
    /**
     * @var SaysRepository
     */
    protected $repository;

    /**
     * SaysController constructor.
     * @param SaysRepository $repository
     */
    public function __construct(SaysRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 达人说
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $says = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $says);
    }
    /**
     * 详情.
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail()
    {
        try {
            $id = request('id');
//            $detail = $this->tbk->getArticle($id);
            $data = new Taobao();
            $detail = $data->getArticle($id);

            return json(1001, '获取成功', $detail);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
