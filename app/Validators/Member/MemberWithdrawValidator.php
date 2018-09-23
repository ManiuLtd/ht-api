<?php

namespace App\Validators\Member;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class MemberWithdrawValidator.
 */
class MemberWithdrawValidator extends LaravelValidator
{
    /**
     * Validation Rules.
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
