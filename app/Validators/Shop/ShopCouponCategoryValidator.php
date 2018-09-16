<?php

namespace App\Validators\Shop;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class ShopCouponCategoryValidator
 * @package App\Validators\Shop
 */
class ShopCouponCategoryValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'status' => 'integer|in:0,1,2',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'status' => 'integer|in:0,1,2',
        ],
    ];
}
