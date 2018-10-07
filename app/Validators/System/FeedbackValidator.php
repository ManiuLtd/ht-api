<?php

namespace App\Validators\System;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class FeedbackValidator.
 */
class FeedbackValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [

        ValidatorInterface::RULE_CREATE => [
            'content'=> 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'content'=> 'required',
        ],
    ];
}
