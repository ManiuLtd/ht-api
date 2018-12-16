<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class EntranceValidator.
 */
class EntranceValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [

        ValidatorInterface::RULE_CREATE => [
            'title'       => 'required',
            'category_id' => 'required',
            'type'        => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
    protected $messages = [
        'title.required'       => '名称不能为空',
        'category_id.required' => '分类不能为空',
        'type.required'        => '类型不能为空',
    ];
}
