<?php

namespace App\Validators\Shop;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class ShopOrderValidator.
 */
class ShopOrderValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [
//            'status' => 'integer|in:0,1,2',
        ],
    ];
    protected $messages = [
//        'integer' => '状态参数必须是整数',
//        'in' => '状态参数错误',
    ];
}
