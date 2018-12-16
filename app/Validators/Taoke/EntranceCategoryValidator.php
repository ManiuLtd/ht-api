<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class EntranceValidator.
 */
class EntranceCategoryValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [

        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'status' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
    protected $messages = [
        'title.required'   => '名称不能为空',
        'status.required' => '状态不能为空',
    ];
}
