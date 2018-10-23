<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class JingxuanDpValidator.
 */
class JingxuanDpValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'itemid' => 'required',
            'title' => 'required',
            'pic_url' => 'required',
            'content' => 'required',
            'price' => 'required',
            'final_price' => 'required',
            'coupon_price' => 'required',
            'commission_rate' => 'required',
            'shares' => 'required',
            'comment1' => 'required',
            'comment2' => 'required',

        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];

    protected $messages = [
        'itemid.required'=> '宝贝id不能为空',
        'title.required'=> '宝贝标题不能为空',
        'pic_url.required' => '宝贝主图原始图像不能为空', //数组
        'content.required' => '单品导购内容不能为空',
        'price.required' => '在售价不能为空',
        'final_price.required' => '宝贝券后价不能为空',
        'coupon_price.required' => '优惠券金额不能为空',
        'commission_rate.required' => '佣金比例不能为空',
        'shares.required' => '该商品被分享次数不能为空',
        'comment1.required' => '导购文案复制内容不能为空',
        'comment2.required' => '朋友圈评论复制内容不能为空',
    ];
}
