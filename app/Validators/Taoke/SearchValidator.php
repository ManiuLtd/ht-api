<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class CouponValidator.
 */
class SearchValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [
            'type'=>'required|in:1,2,3',
        ],
    ];
    protected $messages = [
        'type.required' => 'type不能为空',
        'type.in' => 'type值非法',
    ];
}
