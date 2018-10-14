<?php
/**
 * Copyright (c) 2018. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

namespace App\Tools\Qrcode;

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
    protected $textEnumArray;

    /**
     * @var  array 所有图片数组
     */
    protected $imageEnumArray;

    /**
     * Qrcode constructor.
     * @param $templatePath
     */
    public function __construct($templatePath)
    {
        $this->image = Image::make ($templatePath);
    }

    /**
     * @param array $textEnumArray
     */
    public function setTextEnumArray(array $textEnumArray): void
    {
        $this->textEnumArray = $textEnumArray;
    }

    /**
     * @param array $imageEnumArray
     */
    public function setImageEnumArray(array $imageEnumArray): void
    {
        $this->imageEnumArray = $imageEnumArray;
    }


    /**
     * 生成二维码
     * @return mixed
     */
    public function generate()
    {
        $this->image->resize ($this->width, $this->height);

        //生成图片
        if (count ($this->imageEnumArray) > 0) {
            foreach ($this->imageEnumArray as $imageEnum) {
                if ($imageEnum instanceof ImageEnum) {
                    throw new \InvalidArgumentException('数组元素必须为App\Tools\Qrcode\ImageEnum实例');
                }
                $insertImage = Image::make ($imageEnum->image)->resize ($imageEnum->width, $imageEnum->height);
                $this->image->insert ($insertImage, $imageEnum->position, $imageEnum->x, $imageEnum->y);
            }
        }
        //生成文字
        if (count ($this->textEnumArray) > 0) {
            foreach ($this->textEnumArray as $textEnum) {
                if ($textEnum instanceof TextEnum) {
                    throw new \InvalidArgumentException('数组元素为App\Tools\Qrcode\TextEnum实例');
                }
                $this->image->text ($textEnum->text, $textEnum->x, $textEnum->y, function ($font) use ($textEnum) {
                    $font->file (public_path ('fonts/msyh.ttf'));
                    $font->size ($textEnum->size);
                    $font->color ($textEnum->color);
                    $font->valign ($textEnum->valign);
                });
            }
        }

        $this->image->save (public_path ($this->savePath));

        return asset ($this->savePath);
    }
}
