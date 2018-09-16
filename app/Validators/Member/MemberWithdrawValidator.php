<?php

namespace App\Validators\Member;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class MemberWithdrawValidator.
 *
 * @package namespace App\Validators;
 */
class MemberWithdrawValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [
            'deduct_money' => 'number',
            'status' => 'integer|in:0,1,2',
            'pay_type' => 'integer|in:1,2,3',
        ],
    ];
}
