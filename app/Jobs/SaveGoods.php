<?php

namespace App\Jobs;

use App\Models\Taoke\Coupon;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class SaveGoods implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $tries = 1;
    /**
     * 需要插入的数据
     * @var
     */
    protected $results;

    /**
     * 爬虫类型
     * @var
     */
    protected $spider;
    /**
     * 优惠券类型
     * @var
     */
    protected $tag;

    /**
     * 是否抓取所有产品
     * @var
     */
    protected $all;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($results, $spider, $tag='total', $all=true)
    {
        $this->results = $results;
        $this->spider = $spider;
        $this->tag = $tag;
        $this->all = $all;


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->spider) {
            case 'taobao':
                $this->saveDTKGoods($this->results, $this->tag);
                break;
            case 'jingdong':
                $this->saveJTTGoods($this->results);
                break;
            case 'pinduoduo':
                $this->savePDDGoods($this->results);
            default:
                break;
        }
    }

    /**
     * 淘宝--大淘客
     * @param $results
     * @param $tag
     */
    protected function saveDTKGoods($results, $tag)
    {
        $coupon =new Coupon();
        $inserts = [];
        foreach ($results as $result) {
            $data['title'] = $result->Title;
//            $data['short_title'] = $result->D_title;
            $data['cat'] = $this->setDTKCat($result->Cid);
            $data['shop_type'] = $result->IsTmall ? 2:1;
            $data['pic_url'] = $this->setPicURL($result->Pic);
            $data['item_id'] = $result->GoodsID;
            $data['item_url'] = $result->Jihua_link;
            $data['volume'] = $result->Sales_num;
            $data['price'] = $result->Org_Price;
            $data['final_price'] = $result->Price;
            $data['coupon_price'] = $result->Quan_price;
            $data['activity_id'] = $result->Quan_id;
            $data['commission_rate'] = $this->getCommissionRate($result);
            $data['introduce'] = trim($result->Introduce);
            $data['total_num'] = $result->Quan_surplus + $result->Quan_receive;
            $data['receive_num'] = $result->Quan_receive;
            $data['tag'] = $tag;
            $data['type'] = 1;
            $data['status'] = 0;
            $data['start_time'] = Carbon::now()->toDateTimeString();
            $data['end_time'] = $result->Quan_time;

            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $inserts[] = $data;
            //不是全部抓取 更新单条产品
            if ($this->all == 'false') {
                $data['status'] = 1;
                $coupon->updateOrCreate(
                    ['item_id' => $data['item_id'], 'type' => 'total'],
                    $data
                );
            }
        }
        //批量插入
        if ($this->all == 'true') {
            DB::table('tbk_coupons')->insert($inserts);
        }
        return;
    }

    /**
     * 京推推
     * @param $results
     */
    protected function saveJTTGoods($results)
    {
        $coupon =new Coupon();
        $inserts = [];
        foreach ($results as $result) {
            $data['title'] = $result->goods_name;
            $data['cat'] = $this->setJTTCat($result->goods_type);
            $data['pic_url'] = $result->goods_img;
            $data['item_id'] = $result->goods_id;
            $data['item_url'] = $result->goods_link;
            $data['price'] = $result->goods_price;
            $data['final_price'] = $result->coupon_price;
            $data['coupon_price'] = $result->discount_price;
            $data['coupon_link'] = $result->discount_link;
            $data['commission_rate'] = $result->commission;
            $data['introduce'] = $result->goods_content;
            $data['type'] = 2;
            $data['status'] = 0;
            $data['start_time'] = Carbon::createFromTimestamp(intval($result->discount_start / 1000))->toDateTimeString();
            $data['end_time'] = Carbon::createFromTimestamp(intval($result->discount_end / 1000))->toDateTimeString();;
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $inserts[] = $data;
            //不是全部抓取 更新单条产品
            if ($this->all == 'false') {
                $coupon::updateOrCreate(
                    ['item_id' => $data['item_id']],
                    $data
                );
            }
        }
        //批量插入
        if ($this->all == 'true') {
            DB::table('tbk_coupons')->insert($inserts);
        }
        return;
    }

    /**
     * 多多进宝
     * @param $results
     */
    protected function savePDDGoods($results)
    {
        $coupon =new Coupon();
        $inserts = [];
        foreach ($results as $result) {
            $data['title'] = $result->goods_name;
            $data['cat'] = $result->category_id;
            $data['pic_url'] = $result->goods_image_url;
            $data['item_id'] = $result->goods_id;
            $data['volume'] = $result->sold_quantity;
            $data['price'] = $result->min_group_price /100;
            $data['final_price'] = $result->min_group_price/100 - $result->coupon_discount/100;
            $data['coupon_price'] = $result->coupon_discount/100;

            $data['commission_rate'] = $result->promotion_rate/10;
            $data['introduce'] = $result->goods_desc;
            $data['total_num'] = $result->coupon_total_quantity;
            $data['receive_num'] = $result->coupon_total_quantity - $result->coupon_remain_quantity;
            $data['type'] = 3;
            $data['status'] = 0;
            $data['start_time'] = Carbon::createFromTimestamp(intval($result->coupon_start_time / 1000))->toDateTimeString();
            $data['end_time'] = Carbon::createFromTimestamp(intval($result->coupon_end_time / 1000))->toDateTimeString();;
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $inserts[] = $data;
            //不是全部抓取 更新单条产品
            if ($this->all == 'false') {
                $coupon::updateOrCreate(
                    ['item_id' => $data['item_id']],
                    $data
                );
            }
        }
        //批量插入
        if ($this->all == 'true') {
            DB::table('tbk_coupons')->insert($inserts);
        }
        return;
    }

    /**
     * 获取佣金比例
     * @param $result
     * @return int
     */
    private function getCommissionRate($result)
    {
//        if (isset($result->Commission)) {
//            return $result->Commission;
//        }
        if (isset($result->Commission_jihua)) {
            if ($result->Commission_jihua > 0) {
                return $result->Commission_jihua;
            }

            return $result->Commission_queqiao;
        }
        return 0;
    }

    /**
     * 过滤图片地址
     * @param $url
     * @return mixed|string
     */
    private function setPicURL($url)
    {

        $url = trim($url);

        $url = str_replace("http://", "https://", $url);

        if (str_contains($url, '/tfscom')) {
            $pos = strpos($url, '/tfscom');
            $domain = substr($url, 0, $pos);
            $url = str_replace($domain, 'https://img.alicdn.com', $url);
        }

        if (starts_with($url, '//')) {
            $url = str_replace("//", "https://", $url);
        }
        return $url;
    }

    /**
     * 设置大淘客分类ID
     * @param $cat
     * @return int
     */
    private function setDTKCat($cat)
    {
        switch ($cat) {
            case 1:  //女装
                return 9;
            case 9: //男装
                return 11;
            case 10: //内衣
                return 10;
            case 2: //母婴
                return 2;
            case 3: //化妆品
                return 3;
            case 4: //居家
                return 4;
            case 5: //鞋包配饰
                return 1;
            case 6: //美食
                return 6;
            case 7: //文体车品
                return 7;
            case 8: //数码家电
                return 8;
            default:
                return 12;
        }
    }

    /**
     * 设置京推推分类ID
     * @param $cat
     * @return int
     */
    private function setJTTCat($cat)
    {
        switch ($cat) {
            case 1:  //女装
                return 15;
            case 2: //男装
                return 16;
            case 3: //内衣配饰
                return 17;
            case 4: //母婴玩具
                return 18;
            case 5: //美妆个护
                return 19;
            case 6: //食品保健
                return 20;
            case 7: //居家生活
                return 21;
            case 8: //鞋品箱包
                return 22;
            case 9: //运动户外
                return 23;
            case 10: //文体车品
                return 24;
            case 11: //数码家电
                return 25;
            default:
                return 26;
        }
    }
}
