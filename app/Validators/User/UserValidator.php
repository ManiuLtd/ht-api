<?php

namespace App\Validators\User;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class UserValidator.
 *
 * @package namespace App\Validators;
 */
class UserValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */


    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'email' => 'required|email|unique:users',
            'name' => 'required|unique:users',
            'password' => 'required',
            'status' => 'in:0,1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'status' => 'in:0,1',
        ],
    ];

    protected $messages = [
//        'password.confirmed' => '确认密码不一致'
    ];
}
