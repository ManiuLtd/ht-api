<?php


namespace App\Tools;

use Intervention\Image\Facades\Image;

class Qrcode
{

    /**
     * @var \Intervention\Image\Image
     */
    protected $image;

    /**
     * @var
     */
    public $width;

    /**
     * @var
     */
    public $height;

    /**
     * @var
     */
    public $savePath;

    /**
     * @var  array 所有文字数组
     */
    protected $textArray;

    /**
     * @var  array 所有图片数组
     */
    protected $imageArray;

    /**
     * Qrcode constructor.
     * @param $templatePath
     */
    public function __construct($templatePath)
    {
        $this->image = Image::make ($templatePath);
    }


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
     * @param $width
     * @param $height
     */
    public function resize($width, $height)
    {
        $this->image->resize ($width, $height);
    }


    /**
     * 生成二维码
     * @return mixed
     */
    public function generate()
    {

        $this->image->resize ($this->width, $this->height);


        //生成图片
        if (count ($this->imageArray) > 0) {
            foreach ($this->imageArray as $imgArr) {
                $insertImage = Image::make($imgArr['image'])->resize($imgArr['width'],$imgArr['height']);
                $this->image->insert ($insertImage, $imgArr['position'], $imgArr['x'], $imgArr['y']);
            }
        }
        //生成文字
        if (count ($this->textArray) > 0) {
            foreach ($this->textArray as $textArr) {
                $this->image->text ($textArr['text'], $textArr['x'], $textArr['y'], function ($font) use ($textArr) {
                    $font->file (public_path ('fonts/msyh.ttf'));
                    $font->size ($textArr['size']);
                    $textArr['color'] ? $font->valign ($textArr['color']) : $font->valign ('#444');
                    $textArr['valign'] ? $font->valign ($textArr['valign']) : $font->valign ('left');
                });
            }
        }

        $this->image->save (public_path ($this->savePath));
        return asset ($this->savePath);
    }
}
