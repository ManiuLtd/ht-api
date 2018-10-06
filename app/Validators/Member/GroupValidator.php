<?php

namespace App\Validators\Member;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class GroupValidator
 * @package App\Validators\Member
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
            'name' => 'required',
            'logo' => 'required|url',
            'description' => 'required',
            'status' => 'required|in:0,1',
            'default' => 'required|in:0,1',
        ],
    ];
}
