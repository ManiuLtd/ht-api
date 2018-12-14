<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class DianOrderValidator.
 */
class DianOrderValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [

        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required',
        ],
    ];
    protected $messages = [
        'title.required' => '订单标题不能为空',
    ];
}
