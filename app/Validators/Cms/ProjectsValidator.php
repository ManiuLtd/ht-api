<?php

namespace App\Validators\Cms;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class ProjectsValidator.
 */
class ProjectsValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'user_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'thumb' => 'required|url',
            'sort' => 'required|integer',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'user_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'thumb' => 'required|url',
            'sort' => 'required|integer',
        ],
    ];
}
