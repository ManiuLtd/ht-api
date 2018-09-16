<?php

namespace App\Validators\Member;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class MemberLevelValidator.
 *
 * @package namespace App\Validators\Member;
 */
class MemberLevelValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required',
            'logo' => 'required|url',
            'level' => 'required|integer',
            'discount' => 'required|numeric',
            'ordermoney' => 'required|numeric',
            'ordernum' => 'required|integer',
            'sort' => 'required|integer',
            'status' => 'required|in:0,1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
            'logo' => 'required|url',
            'level' => 'required|integer',
            'discount' => 'required|numeric',
            'ordermoney' => 'required|numeric',
            'ordernum' => 'required|integer',
            'sort' => 'required|integer',
            'status' => 'required|in:0,1',
        ],
    ];
}
