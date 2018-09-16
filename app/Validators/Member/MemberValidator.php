<?php

namespace App\Validators\Member;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class MemberValidator.
 *
 * @package namespace App\Validators\Member;
 */
class MemberValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'phone' => 'required|unique:members',
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
