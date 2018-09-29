<?php

namespace App\Validators\Cms;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class CategoriesValidator.
 */
class ArticleValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'user_id' => 'required|integer',
            'title' => 'required',
            'category_id' => 'required|integer',
            'thumb' => 'required|url',
            'keywords' => 'required',
            'description' => 'required',
            'content' => 'required',
            'views' => 'required|integer',
            'sort' => 'required|integer',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required',
            'category_id' => 'required|integer',
            'thumb' => 'required|url',
            'keywords' => 'required',
            'description' => 'required',
            'content' => 'required',
            'sort' => 'required|integer',
        ],
    ];
}
