<?php

namespace App\Http\Controllers\Backend\Member;

use App\Http\Controllers\Controller;
use App\Validators\Member\MemberFavouriteValidator;
use App\Repositories\Interfaces\MemberFavouriteRepository;

/**
 * Class MemberFavouritesController.
 */
class MemberFavouritesController extends Controller
{
    /**
     * @var MemberFavouriteRepository
     */
    protected $repository;

    /**
     * @var MemberFavouriteValidator
     */
    protected $validator;

    /**
     * MemberFavouritesController constructor.
     *
     * @param MemberFavouriteRepository $repository
     * @param MemberFavouriteValidator $validator
     */
    public function __construct(MemberFavouriteRepository $repository, MemberFavouriteValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 收藏列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $memberFavourites = $this->repository
            ->with('goods')
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $memberFavourites);
    }
}
