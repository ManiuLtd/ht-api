<?php

namespace App\Tools\Qrcode;

class TextEnum
{
    /**
     * @var string
     */
    public $text;

    /**
     * @var float
     */
    public $x;

    /**
     * @var float
     */
    public $y;

    /**
     * @var string
     */
    public $size;

    /**
     * @var
     */
    public $color;

    /**
     * @var
     */
    public $valign;

    /**
     * TextEnum constructor.
     * @param string $text
     * @param float $x
     * @param float $y
     * @param string $size
     * @param string $color
     * @param string $valign
     */
    public function __construct(string $text, float $x, float $y, string $size, string $color = '#444', string $valign = 'left')
    {
        $this->text = $text;
        $this->x = $x;
        $this->y = $y;
        $this->size = $size;
        $this->color = $color;
        $this->valign = $valign;
    }
}
