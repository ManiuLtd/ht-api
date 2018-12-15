<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class DianValidator.
 */
class DiyZhuantiValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [

        ValidatorInterface::RULE_CREATE => [
        ],
        ValidatorInterface::RULE_UPDATE => [

        ],
    ];
    protected $messages = [

    ];
}
