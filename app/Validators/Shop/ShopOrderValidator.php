<?php

namespace App\Validators\Shop;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class ShopOrderValidator
 * @package App\Validators\Shop
 */
class ShopOrderValidator extends LaravelValidator
{
    /**
     * Validation Rules
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
