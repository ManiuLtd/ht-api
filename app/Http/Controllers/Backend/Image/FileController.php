<?php

namespace App\Http\Controllers\Backend\Image;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Class FileController.
 */
class FileController extends Controller
{
    /**
     * 上传图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {

        // 上传文件到云储存
        $file = $request->file ('file');
        if (!$file) {
            return json ('4001', '上传失败，没接受到file');
        }

        //图片
        $mimeType = $file->getClientMimeType ();
        if (!str_contains ($mimeType, 'image/')) {
            return json ('4001', '图片格式错误');
        }
        $path = 'images/' . date ('Ymd');

        $filePath = $file->store ($path);

        return json (1001, '上传成功', ['url' => Storage::url ($filePath)]);
    }
}
