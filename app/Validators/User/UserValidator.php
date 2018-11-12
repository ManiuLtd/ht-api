<?php

namespace App\Validators\User;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class UserValidator.
 */
class UserValidator extends LaravelValidator
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
            'credit2' => 'numeric',
            'level1' => 'integer',
            'level2' => 'integer',
            'status' => 'in:0,1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'credit1' => 'numeric',
            'credit2' => 'numeric',
            'level1' => 'integer',
            'level2' => 'integer',
            'status' => 'in:0,1',
        ],
    ];
}
