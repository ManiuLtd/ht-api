<?php

namespace App\Http\Controllers\Backend\Image;

use App\Http\Controllers\Controller;

/**
 * Class ImagesController.
 */
class ImagesController extends Controller
{
    /**
     * 图片列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //TODO 显示储存空间的图片列表（阿里云或者七牛云)
        return json(1001, 'image list get success');
    }
}
