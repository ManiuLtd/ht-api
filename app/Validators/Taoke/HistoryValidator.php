<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class HistoryValidator.
 */
class HistoryValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'pic_url' => 'required',
            'item_id' => 'required',
            'volume' => 'required|numeric',
            'price' => 'required|numeric',
            'coupon_price' => 'required|numeric',
            'final_price' => 'required|numeric',
            'type' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
