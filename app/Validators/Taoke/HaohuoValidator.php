<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class HaohuoValidator
 * @package App\Validators\Taoke
 */
class HaohuoValidator extends LaravelValidator
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
            'app_hot_image' => 'required|url',
            'shares' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required',
            'content' => 'required',
            'introduce' => 'required',
            'app_hot_image' => 'required|url',
            'shares' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ],
    ];
}
