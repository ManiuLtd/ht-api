<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Image;


use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Image\BannerRepository;
use Illuminate\Support\Facades\Request;
use Mockery\Exception;

/**
 * 图片管理
 * Class OrdersController
 * @package App\Http\Controllers\Api\Member
 */
class BannersController extends Controller
{

    /**
     * FriendsController constructor.
     */
    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerrepository = $bannerRepository;
    }

    //TODO 图标列表 可根据tag查看,根据sort排序
    public function index(Request $request)
    {
        try{
            $where = [];
            $tag = $request->tag;
            if($tag){
                $where['tag'] = $tag;
            }
            $image = db('banners')
                ->where($where)
                ->orderBy('sort','desc')
                ->get();
            return json('1001','图标列表获取成功',$image);
        }catch (Exception $e)
        {
            return json('5001',$e->getMessage());
        }
    }
}