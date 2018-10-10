<?php

namespace App\Validators\Member;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class CommissionLevelValidator.
 */
class CommissionLevelValidator extends LaravelValidator
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
            'min_commission' => 'required',
            'friends1' => 'required',
            'friends2' => 'required',
            'ordernum' => 'required',
            'price' => 'required',
            'duration' => 'required',
            'description' => 'required',
            'is_commission' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
