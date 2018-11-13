<?php

namespace App\Validators\User;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;


/**
 * Class WithdrawValidator.
 */
class WithdrawValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'realname' => 'required',
//            'bankname' => 'required',
//            'bankcard' => 'required|numeric',
            'alipay' => 'required',
            'money' => 'required|numeric',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'real_money' => 'required',
            'reason' => 'required',
        ],
    ];

    /**
     * @var array
     */
    protected $messages = [
        'realname.required' => '姓名不能为空',
//        'bankname.required' => '银行名称不能为空',
//        'bankcard.numeric' => '银行卡号不合法',
//        'bankcard.required' => '银行卡号不能为空',
        'alipay.required' => '支付宝不能为空',
        'money.numeric' => '提现金额不合法',
        'money.required' => '提现金额不能为空',
    ];


}
