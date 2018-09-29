<?php

namespace App\Validators\Cms;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class CategoriesValidator.
 */
class CategoriesValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required',
            'logo' => 'required|url',
            'sort' => 'required|integer',
            'type' => 'required|in:1,2',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
            'logo' => 'required|url',
            'sort' => 'required|integer',
            'type' => 'required|in:1,2',
        ],
    ];
}
