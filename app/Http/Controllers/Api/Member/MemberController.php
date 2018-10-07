<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Member;


use App\Http\Controllers\Controller;

/**
 * 会员管理
 * Class MemberController
 * @package App\Http\Controllers\Api\Member
 */
class MemberController extends Controller
{

    /**
     * MemberController constructor.
     */
    public function __construct()
    {
    }

    //TODO 获取当前会员信息，包括用户等级等
    public function index()
    {

    }
    //TODO 好友列表  把FriendsController的代码移动到这里 可根据inviter_id查看
    public function friends()
    {

    }
}