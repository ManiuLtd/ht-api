<?php

namespace App\Validators\Member;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class LevelValidator.
 */
class LevelValidator extends LaravelValidator
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
            'group_rate1' => 'required',
            'group_rate2' => 'required',
            'commission_rate1' => 'required',
            'commission_rate2' => 'required',
            'credit' => 'required',
            'price' => 'required',
            'duration' => 'required',
            'description' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
            'logo' => 'required|url',
            'level' => 'required|integer',
            'group_rate1' => 'required',
            'group_rate2' => 'required',
            'commission_rate1' => 'required',
            'commission_rate2' => 'required',
            'credit' => 'required',
            'price' => 'required',
            'duration' => 'required',
            'description' => 'required',
        ],
    ];
}
