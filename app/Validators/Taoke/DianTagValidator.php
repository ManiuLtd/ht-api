<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class DianValidator.
 */
class DianTagValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [

        ValidatorInterface::RULE_CREATE => [
            'name' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
        ],
    ];
    protected $messages = [
        'name.required' => '分类不能为空',
    ];
}
