<?php

namespace App\Validators\Taoke;

use Prettus\Validator\LaravelValidator;

/**
 * Class QrcodeValidator.
 */
class QrcodeValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        'pic_url' => 'required',
        'type' => 'required',
        'title' => 'required',
        'final_price' => 'required',
        'coupon_price' => 'required',
        'volume' => 'required',
        'item_id' => 'required',
    ];
    protected $messages = [
        'pic_url.required' => '封面不能为空',
        'type.required' => '类型不能为空',
        'title.required' => '名称不能为空',
        'final_price.required' => '最终金额不能为空',
        'coupon_price.required' => '优惠卷金额不能为空',
        'volume.required' => '销量不能为空',
        'item_id.required' => '优惠卷id不能为空',
    ];
}
