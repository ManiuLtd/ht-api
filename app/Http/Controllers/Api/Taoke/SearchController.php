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
use App\Validators\Taoke\SearchValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
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
     * @param SearchValidator $validator
     * @param CouponRepository $couponRepository
     */
    public function __construct(TBKInterface $TBK, SearchValidator $validator, CouponRepository $couponRepository)
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
            $this->validator->with (request ()->all ())->passesOrFail ();

            $rest = $this->TBK->search ();

            return json (1001, '获取成功', $rest);
        } catch (\Exception $exception) {
            return json (5001, $exception->getMessage ());
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function keywords()
    {
        try {
            $this->validator->with (request ()->all ())->passesOrFail (ValidatorInterface::RULE_UPDATE);

            $resp = $this->TBK->hotSearch ();

            return json (1001, '获取成功', $resp);
        } catch (\Exception $exception) {
            return json (5001, $exception->getMessage ());
        }
    }
}
