<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class FavouriteValidator.
 */
class FavouriteValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'pic_url' => 'required',
            'item_id' => 'required',
            'volume' => 'required|int',
            'price' => 'required|numeric',
            'coupon_price' => 'required|numeric',
            'final_price' => 'required|numeric',
            'type' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
