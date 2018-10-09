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
     * 上传文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
//        $disk = storage();

        // 上传文件到云储存
        $file = $request->file('file');
        if (!$file) {
            return response()->json([
                'status' => '500',
                'message' => '上传失败',
            ]);
        }
        if (request('type') == 2) {
            //获取上传目录
            $fileName = $file->getClientOriginalName();
            if (!in_array($fileName, ['apiclient_key.pem', 'apiclient_cert.pem'])){
                return response()->json([
                    'status' => '500',
                    'message' => '请上传对应名称的证书',
                ]);
            }
            $fileExtension = $file->getClientOriginalExtension();
            if ($fileExtension != "pem") {
                return response()->json([
                    'status' => '500',
                    'message' => '证书格式错误',
                ]);
            }
            $filePath = Storage::disk('local')->putFileAs('apiclient/' . Hashids::encode(session('uuid')), $file, $fileName);
            return json(1001,'上传成功',[
                'status' => '200',
                'url' => $filePath,
            ]);
        } else {
            //图片
            $mimeType = $file->getClientMimeType();
            if (!str_contains($mimeType, 'image/')) {
                return response()->json([
                    'status' => '500',
                    'message' => '图片格式错误',
                ]);
            }
            $path = 'images/' . date('Ymd');
            $filePath = $file->store($path);
            return json(1001,'上传成功',[
                'status' => '200',
                'url' => Storage::url($filePath),
            ]);
        }

    }
}
