<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class HaohuoValidator.
 */
class HaoHuoValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'content' => 'required',
            'introduce' => 'required',
            'shares' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'text' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required',
            'itemid' => 'required',
            'content' => 'required',
            'introduce' => 'required',
            'shares' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'text' => 'required',
        ],
    ];
}
