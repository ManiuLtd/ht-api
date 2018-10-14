<?php
/**
 * Copyright (c) 2018. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/14
 * Time: 16:11
 */

namespace App\Tools\Qrcode;

/**
 * Class ImageEnum
 * @package App\Tools\Qrcode
 */
class ImageEnum
{

    /**
     * @var string
     */
    public $image;

    /**
     * @var float
     */
    public $width;

    /**
     * @var float
     */
    public $height;

    /**
     * @var string
     */
    public $position;

    /**
     * @var float
     */
    public $x;

    /**
     * @var float
     */
    public $y;


    /**
     * ImageEnum constructor.
     * @param string $image
     * @param float $width
     * @param float $height
     * @param string $position
     * @param float $x
     * @param float $y
     */
    public function __construct(string $image, float $width, float $height, string $position, float $x, float $y)
    {
        $this->image = $image;
        $this->width = $width;
        $this->height = $height;
        $this->position = $position;
        $this->x = $x;
        $this->y = $y;
    }

}