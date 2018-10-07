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
        'name' => 'required',
        'type' => 'required',
        'status' => 'required',

//        ValidatorInterface::RULE_CREATE => [],
//        ValidatorInterface::RULE_UPDATE => [],
    ];
    protected $messages = [
        'name.required' => '分类名不能为空',
        'type.required' => '类型不能为空',
        'status.required' => '状态不能为空',
    ];
}
