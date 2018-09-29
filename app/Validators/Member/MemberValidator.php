<?php

namespace App\Validators\Member;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class MemberValidator.
 */
class MemberValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'phone' => 'unique:members',
            'password' => 'required',
            'credit1' => 'numeric',
            'credit2' => 'integer',
            'level1' => 'integer',
            'level2' => 'integer',
            'status' => 'in:0,1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'credit1' => 'numeric',
            'credit2' => 'integer',
            'level1' => 'integer',
            'level2' => 'integer',
            'status' => 'in:0,1',
        ],
    ];
}
