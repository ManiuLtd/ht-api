<?php

namespace App\Validators\Shop;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class ShopCouponCategoryValidator.
 */
class ShopCouponCategoryValidator extends LaravelValidator
{
    /**
     * Validation Rules.
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
