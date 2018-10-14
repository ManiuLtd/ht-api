<?php

namespace App\Tools\Qrcode;

/**
 * Class ImageEnum.
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
