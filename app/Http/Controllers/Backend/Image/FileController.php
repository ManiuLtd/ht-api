<?php

namespace App\Http\Controllers\Backend\Image;


use App\Http\Controllers\Controller;

/**
 * Class FileController
 * @package App\Http\Controllers\Backend\Image
 */
class FileController extends Controller
{
    /**
     * 上传文件
     * https://github.com/jacobcyl/Aliyun-oss-storage
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $disk = storage ();
        //TODO 上传文件到云储存
        return json (1001, 'upload success');
    }
}
