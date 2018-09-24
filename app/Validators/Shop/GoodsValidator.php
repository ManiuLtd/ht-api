<?php

namespace App\Validators\Shop;

use Prettus\Validator\LaravelValidator;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class GoodsValidator.
 */
class GoodsValidator extends LaravelValidator
{
    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'thumb' => 'array',
            'price' => 'required|numeric',
            'old_price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'total' => 'required|integer',
            'totalcnf' => 'required|in:0,1',
            'sales' => 'required|integer',
            'real_sales' => 'required|integer',
            'show_sales' => 'required|in:0,1',
            'show_spec' => 'required|in:0,1',
            'weight' => 'required|numeric',
            'minbuy' => 'required|integer',
            'maxbuy' => 'required|integer',
            'total_maxbuy' => 'required|integer',
            'hasoption' => 'required|in:0,1',
            'isnew' => 'required|in:0,1',
            'ishot' => 'required|in:0,1',
            'isrecommend' => 'required|in:0,1',
            'isdiscount' => 'required|in:0,1',
            'issendfree' => 'required|in:0,1',
            'iscomment' => 'required|in:0,1',
            'views' => 'required|integer',
            'hascommission' => 'required|in:0,1',
            'commission0_rate' => 'numeric',
            'commission0_pay' => 'numeric',
            'commission1_rate' => 'numeric',
            'commission1_pay' => 'numeric',
            'commission2_rate' => 'numeric',
            'commission2_pay' => 'numeric',
            'commission3_rate' => 'numeric',
            'commission3_pay' => 'numeric',
            'is_not_discount' => 'required|in:0,1',
            'deduct_credit1' => 'required|in:0,1',
            'deduct_credit2' => 'required|in:0,1',
            'dispatch_type' => 'required|in:0,1',
            'dispatch_price' => 'numeric',
            'show_total' => 'required|in:0,1',
            'auto_receive' => 'required|in:0,1',
            'can_not_refund' => 'required|in:0,1',
            'type' => 'required|in:1,2,3',
            'status' => 'required|in:0,1',
            'sort' => 'required|integer'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required',
            'thumb' => 'array',
            'price' => 'required|numeric',
            'old_price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'total' => 'required|integer',
            'totalcnf' => 'required|in:0,1',
            'sales' => 'required|integer',
            'real_sales' => 'required|integer',
            'show_sales' => 'required|in:0,1',
            'show_spec' => 'required|in:0,1',
            'weight' => 'required|numeric',
            'minbuy' => 'required|integer',
            'maxbuy' => 'required|integer',
            'total_maxbuy' => 'required|integer',
            'hasoption' => 'required|in:0,1',
            'isnew' => 'required|in:0,1',
            'ishot' => 'required|in:0,1',
            'isrecommend' => 'required|in:0,1',
            'isdiscount' => 'required|in:0,1',
            'issendfree' => 'required|in:0,1',
            'iscomment' => 'required|in:0,1',
            'views' => 'required|integer',
            'hascommission' => 'required|in:0,1',
            'commission0_rate' => 'numeric',
            'commission0_pay' => 'numeric',
            'commission1_rate' => 'numeric',
            'commission1_pay' => 'numeric',
            'commission2_rate' => 'numeric',
            'commission2_pay' => 'numeric',
            'commission3_rate' => 'numeric',
            'commission3_pay' => 'numeric',
            'is_not_discount' => 'required|in:0,1',
            'deduct_credit1' => 'required|in:0,1',
            'deduct_credit2' => 'required|in:0,1',
            'dispatch_type' => 'required|in:0,1',
            'dispatch_price' => 'numeric',
            'show_total' => 'required|in:0,1',
            'auto_receive' => 'required|in:0,1',
            'can_not_refund' => 'required|in:0,1',
            'type' => 'required|in:1,2,3',
            'status' => 'required|in:0,1',
            'sort' => 'required|integer'
        ],
    ];
}
