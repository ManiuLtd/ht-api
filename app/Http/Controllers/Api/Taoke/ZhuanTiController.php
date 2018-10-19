<?php


namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Validators\Taoke\ZhuanTiValidator;
use App\Repositories\Interfaces\Taoke\ZhuanTiRepository;

/**
 * Class ZhuanTiController.
 */
class ZhuanTiController extends Controller
{
    /**
     * @var ZhuanTiRepository
     */
    protected $repository;

    /**
     * @var ZhuanTiValidator
     */
    protected $validator;

    /**
     * ZhuanTiController constructor.
     *
     * @param ZhuanTiRepository $repository
     * @param ZhuanTiValidator $validator
     */
    public function __construct(ZhuanTiRepository $repository, ZhuanTiValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository->paginate(request('limit', 10));

        return json(1001, '获取成功', $categories);
    }
}
