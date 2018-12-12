<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class DianValidator.
 */
class DianValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [

        ValidatorInterface::RULE_CREATE => [
            'category_id' => 'required',
            'thumb' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'category_id' => 'required',
            'thumb' => 'required',
        ],
    ];
    protected $messages = [
        'name.required' => '所属分类不能为空',
        'thumb.required' => '主图不能为空',
    ];
}
