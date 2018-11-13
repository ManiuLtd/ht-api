<?php

namespace App\Validators\User;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class GroupValidator.
 */
class GroupValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [
            'member_id' => 'required|integer',
            'pid' => 'required|integer',
            'name' => 'required',
            'qrcode' => 'required',
            'qq' => 'required',
            'wechat' => 'required',
            'logo' => 'required|url',
            'description' => 'required',
            'status' => 'required|in:0,1',
            'default' => 'required|in:0,1',
        ],
    ];
}
