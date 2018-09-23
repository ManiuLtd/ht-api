<?php

namespace App\Validators\Member;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class MemberLevelValidator.
 */
class MemberLevelValidator extends LaravelValidator
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
            'level' => 'required|integer',
            'discount' => 'required|numeric',
            'credit' => 'required|integer',
            'sort' => 'required|integer',
            'status' => 'required|in:0,1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
            'logo' => 'required|url',
            'level' => 'required|integer',
            'discount' => 'required|numeric',
            'credit' => 'required|integer',
            'sort' => 'required|integer',
            'status' => 'required|in:0,1',
        ],
    ];
}
