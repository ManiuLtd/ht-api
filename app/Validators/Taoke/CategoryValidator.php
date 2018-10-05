<?php

namespace App\Validators\Taoke;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CategoryValidator.
 *
 * @package namespace App\Validators\Taoke;
 */
class CategoryValidator extends LaravelValidator
{
    /**
     * Validation Rules
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
