<?php

namespace App\Validators\Taoke;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class QuanValidator
 * @package App\Validators\Taoke
 */
class QuanValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'user_id' => 'required|integer',
            'item_id' => 'required|integer',
            'nickname' => 'required',
            'headimg' => 'required|url',
            'introduce' => 'required',
            'taokouling' => 'required',
            'shares' => 'required|integer',
            'type' => 'required|in:1,2,3,4',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'item_id' => 'required|integer',
            'nickname' => 'required',
            'headimg' => 'required|url',
            'introduce' => 'required',
            'taokouling' => 'required',
            'shares' => 'required|integer',
            'type' => 'required|in:1,2,3,4',
        ],
    ];
}
