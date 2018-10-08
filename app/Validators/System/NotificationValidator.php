<?php

namespace App\Validators\System;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class NotificationValidator.
 */
class NotificationValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'msg_id' => 'required|integer',
            'logo' => 'required|url',
            'title' => 'required',
            'message' => 'required',
            'type' => 'required|in:1,2',
//            'type' => 'required|in:2',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
