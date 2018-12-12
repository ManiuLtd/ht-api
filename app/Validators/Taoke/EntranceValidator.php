<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class EntranceValidator
 * @package App\Validators\Taoke
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
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
    protected $messages = [
        'title.required'       => '名称不能为空',
        'category_id.required' => '分类不能为空',
    ];
}
