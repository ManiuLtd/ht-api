<?php

namespace App\Validators\Shop;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;

/**
 * Class ShopCategoryValidator
 * @package App\Validators\Shop
 */
class ShopCategoryValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'parentid' => 'integer',
            'name' => 'required',
            'thumb' => 'url',
            'advimg' => 'url',
            'advurl' => 'url',
            'status' => 'required|in:0,1',
            'ishome' => 'required|in:0,1',
            'isrecommand' => 'required|in:0,1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'parentid' => 'integer',
            'name' => 'required',
            'thumb' => 'url',
            'advimg' => 'url',
            'advurl' => 'url',
            'status' => 'required|in:0,1',
            'ishome' => 'required|in:0,1',
            'isrecommand' => 'required|in:0,1',
        ],
    ];
}
