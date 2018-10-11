<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/6
 * Time: 20:45.
 */

namespace App\Tools;

class Qrcode
{

    /**
     * @var string 模板路径
     */
    protected $templatePath;

    /**
     * @var  array 所有文字数组
     */
    protected $textArray;

    /**
     * @var  array 所有图片数组
     */
    protected $imageArray;

    /**
     * @param array $textArray
     */
    public function setTextArray(array $textArray): void
    {
        $this->textArray = $textArray;
    }

    /**
     * @param array $imageArray
     */
    public function setImageArray(array $imageArray): void
    {
        $this->imageArray = $imageArray;
    }


    /**
     * @param string $templatePath
     */
    public function setTemplatePath(string $templatePath): void
    {
        $this->templatePath = $templatePath;
    }



    public function

    ()
    {
        $template = Image::make($this->templatePath);

        if (count ($this->imageArray)){
            foreach ($this->imageArray as $imgArr){
                $template->insert($imgArr['image'], $imgArr['position'], $imgArr['x'], $imgArr['y']);
            }
        }
        if (count ($this->textArray)){
            foreach ($this->textArray as $textArr){
                $template->text($imgArr['text'], 215, 1485, function ($font) use ($textArr){
                    $font->file(public_path('fonts/yahei.ttf'));
                    $textArr['size'] ?? $font->size($textArr['size']);
                    $font->color('#9b9b9b');
                    $font->valign('left');
                });

                $template->insert($imgArr['text'], $imgArr['position'], $imgArr['x'], $imgArr['y']);
            }
        }
      //储存图片 返回
    }
}
