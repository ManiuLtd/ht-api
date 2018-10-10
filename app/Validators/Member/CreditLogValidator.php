<?php

namespace App\Validators\Member;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class CreditLogValidator.
 */
class CreditLogValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'type' =>'required|in:1,2,3'
        ],

        ValidatorInterface::RULE_UPDATE => [],
    ];

    /**
     * @var array
     */
    protected $messages = [
        'type.required' => '非法的type值',
        'type.in' => '非法的type值',
    ];
}
