<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class ZhuanTiValidator.
 */
class ZhuanTiValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title'      => 'required',
            'thumb'      => 'required|url',
            'banner'     => 'required|url',
            'content'    => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title'      => 'required',
            'thumb'      => 'required|url',
            'banner'     => 'required|url',
            'content'    => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
        ],
    ];
}
