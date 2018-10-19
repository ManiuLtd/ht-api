<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18.
 */

namespace App\Http\Controllers\Api\Taoke;

use App\Tools\Taoke\TBKInterface;
use App\Http\Controllers\Controller;
use App\Validators\Taoke\CouponValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Taoke\CouponRepository;

/**
 * 搜索
 * Class SearchController.
 */
class SearchController extends Controller
{
    /**
     * @var
     */
    protected $TBK;
    /**
     * @var
     */
    protected $validator;
    /**
     * @var
     */
    protected $couponRepository;

    /**
     * SearchController constructor.
     * @param TBKInterface $TBK
     * @param CouponValidator $validator
     * @param CouponRepository $couponRepository
     */
    public function __construct(TBKInterface $TBK, CouponValidator $validator, CouponRepository $couponRepository)
    {
        $this->TBK = $TBK;
        $this->validator = $validator;
        $this->couponRepository = $couponRepository;
    }

    /**
     * 搜索功能，根据关键词搜索，根据type决定决定搜索平台,type必传.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {


        try {
            //TODO 添加SearchValidator
            $this->validator->with(request()->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $array = [
                'type' => request('type'),
                'sort' => request('sort'),
                'page' => request('page', 1),
                'q' => request('q'),
                'keywords' => request('keywords'),
            ];
            if (request('type') == 2) {  //确认是否有全网搜搜
                $rest = $this->couponRepository->searchGoods();
            } else {
                $rest = $this->TBK->search($array);
            }

            unset($rest->code);
            unset($rest->message);

            return json(1001, '获取成功', $rest);
        } catch (\Exception $e) {
            return json(4001, $e->getMessage());
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function keywords()
    {
        try {
            //TODO 添加SearchValidator
            $this->validator->with(request()->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $resp = $this->TBK->hotSearch();

            return json(1001, $resp['message'], $resp['data']);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
