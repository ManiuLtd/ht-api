<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Criteria\UserCriteria;
use App\Tools\Taoke\JingDong;
use App\Tools\Taoke\PinDuoDuo;
use App\Tools\Taoke\Taobao;
use App\Http\Controllers\Controller;
use App\Validators\Taoke\FavouriteValidator;
use App\Http\Requests\Taoke\FavouriteCreateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Repositories\Interfaces\Taoke\FavouriteRepository;

/**
 * Class FavouritesController.
 */
class FavouritesController extends Controller
{


    /**
     * @var FavouriteRepository
     */
    protected $repository;

    /**
     * @var FavouriteValidator
     */
    protected $validator;

    /**
     * FavouritesController constructor.
     *
     * @param FavouriteRepository $repository
     * @param FavouriteValidator $validator
     */
    public function __construct(FavouriteRepository $repository, FavouriteValidator $validator)
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
        $favourites = $this->repository
            ->pushCriteria(new UserCriteria())
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $favourites);
    }

    /**
     * 添加收藏
     * @param FavouriteCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FavouriteCreateRequest $request)
    {
        try {
            $re = $request->all();
            if ($re['type'] == 1){
                $tool    = new Taobao();
            }elseif ($re['type'] == 2){
                $tool    = new JingDong();
            }elseif ($re['type'] == 3){
                $tool    = new PinDuoDuo();
            }
            $detail = $tool->getDetail([
                'itemid' => $re['item_id']
            ]);
            $user = getUser();
            $data = [
                'title'        => $detail['title'] ?? '',
                'pic_url'      => $detail['pic_url'] ?? '',
                'item_id'      => $detail['item_id'] ?? '',
                'volume'       => $detail['volume'] ?? '',
                'coupon_price' => $detail['coupon_price'] ?? '',
                'final_price'  => $detail['final_price'] ?? '',
                'price'        => $detail['price'] ?? '',
                'type'         => $re['type'] ?? '',
                'user_id'      => $user->id ?? '',
            ];
            $favourite = $this->repository->create($data);

            return json(1001, '添加成功', $favourite);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 取消收藏.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '取消成功');
    }
}
