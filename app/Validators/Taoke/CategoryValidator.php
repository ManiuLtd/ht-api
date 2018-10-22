<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class CategoryValidator.
 */
class CategoryValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [

        ValidatorInterface::RULE_CREATE => [
            'name' => 'required',
            'status' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
            'status' => 'required',
        ],
    ];
    protected $messages = [
        'name.required' => '分类名不能为空',
        'status.required' => '状态不能为空',
    ];
}
