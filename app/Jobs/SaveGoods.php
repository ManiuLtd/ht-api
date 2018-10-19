<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Taoke\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveGoods implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $tries = 1;
    /**
     * 需要插入的数据.
     * @var
     */
    protected $results;

    /**
     * 爬虫类型.
     * @var
     */
    protected $spider;
    /**
     * 优惠券类型.
     * 1实时跑单商品，2爆单榜商品，3全部商品，4纯视频单，5聚淘专区
     * @var
     */
    protected $tag;

    /**
     * 是否抓取所有产品
     * @var
     */
    protected $all;

    /**
     * SaveGoods constructor.
     * @param $results
     * @param $spider
     * @param int $tag
     * @param bool $all
     */
    public function __construct($results, $spider, $tag = 3, $all = true)
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
            case 'timingItems':
                $this->saveTimeGoods($this->results);
            default:
                break;
        }
    }

    /**
     * 淘宝--大淘客.
     * @param $results
     * @param $tag
     */
    protected function saveDTKGoods($results, $tag)
    {
        $coupon = new Coupon();
        $inserts = [];
        foreach ($results as $result) {
            $data['title'] = $result->itemtitle;
//            $data['short_title'] = $result->D_title;
            $data['cat'] = $result->fqcat;
            $data['shop_type'] = $result->shoptype == 'B' ? 2 : 1;
            $data['pic_url'] = $result->itempic;
            $data['item_id'] = $result->itemid;

            $data['volume'] = $result->itemsale;
            $data['price'] = $result->itemprice;
            $data['final_price'] = $result->itemendprice;
            $data['coupon_price'] = $result->couponmoney;

            $data['commission_rate'] = $result->tkrates;
            $data['introduce'] = trim($result->guide_article);
            $data['total_num'] = $result->couponnum;
            $data['receive_num'] = $result->couponreceive2;
            $data['tag'] = $tag;//1实时跑单商品，2爆单榜商品，3全部商品，4纯视频单，5聚淘专区
            $data['type'] = 1;
            $data['status'] = 0;
            $data['videoid'] = $result->videoid;
            $data['activity_type'] = $this->GetactivityType($result->activity_type);
            $data['start_time'] = date('Y-m-d H:i:s',$result->couponstarttime);
            $data['end_time'] = date('Y-m-d H:i:s',$result->couponendtime);

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
    }


    /**
     * 京推推.
     * @param $results
     */
    protected function saveJTTGoods($results)
    {
        $coupon = new Coupon();
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
            $data['end_time'] = Carbon::createFromTimestamp(intval($result->discount_end / 1000))->toDateTimeString();
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
    }

    /**
     * 多多进宝.
     * @param $results
     */
    protected function savePDDGoods($results)
    {
        $coupon = new Coupon();
        $inserts = [];
        foreach ($results as $result) {
            $data['title'] = $result->goods_name;
            $data['cat'] = $result->category_id;
            $data['pic_url'] = $result->goods_image_url;
            $data['item_id'] = $result->goods_id;
            $data['volume'] = $result->sold_quantity;
            $data['price'] = $result->min_group_price / 100;
            $data['final_price'] = $result->min_group_price / 100 - $result->coupon_discount / 100;
            $data['coupon_price'] = $result->coupon_discount / 100;

            $data['commission_rate'] = $result->promotion_rate / 10;
            $data['introduce'] = $result->goods_desc;
            $data['total_num'] = $result->coupon_total_quantity;
            $data['receive_num'] = $result->coupon_total_quantity - $result->coupon_remain_quantity;
            $data['type'] = 3;
            $data['status'] = 0;
            $data['start_time'] = Carbon::createFromTimestamp(intval($result->coupon_start_time / 1000))->toDateTimeString();
            $data['end_time'] = Carbon::createFromTimestamp(intval($result->coupon_end_time / 1000))->toDateTimeString();
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
    }

    /**
     * 定时拉取
     * @param $results
     */
    protected function saveTimeGoods($results)
    {
        $coupon = new Coupon();
        $inserts = [];
        foreach ($results as $result) {
            $data['title'] = $result->itemtitle;
            $data['cat'] = $this->setDTKCat($result->fqcat);
            $data['shop_type'] = $result->shoptype == 'B' ? 2 : 1;
            $data['pic_url'] = $result->itempic;
            $data['item_id'] = $result->itemid;
            $data['volume'] = $result->itemsale;
            $data['price'] = $result->itemprice;
            $data['final_price'] = $result->itemprice - $result->couponmoney;
            $data['coupon_price'] = $result->couponmoney;
            $data['commission_rate'] = $result->tkrates;
            $data['introduce'] = $result->itemdesc;
            $data['total_num'] = $result->couponnum;
            $data['receive_num'] = $result->couponreceive2;//当天领取数量
            $data['type'] = 1;//?
            $data['status'] = 0;
            $data['videoid'] = $result->videoid;
            $data['activity_type'] = $this->GetactivityType($result->activity_type);
            $data['start_time'] = Carbon::createFromTimestamp(intval($result->couponstarttime / 1000))->toDateTimeString();
            $data['end_time'] = Carbon::createFromTimestamp(intval($result->couponendtime / 1000))->toDateTimeString();
            $data['starttime'] = $result->start_time ? date('Y-m-d H:i:s',$result->start_time) : '';
            $data['endtime'] = $result->end_time ? date('Y-m-d H:i:s',$result->end_time) : '';
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $inserts[] = $data;
            $coupon::updateOrCreate(
                ['item_id' => $data['item_id']],
                $data
            );
        }
    }
    /**
     * 活动类型：
     * @param $activity_type
     * @return int
     *
     */
    protected function GetactivityType($activity_type)
    {
        switch ($activity_type)
        {
            case '普通活动':
                return 1;
                break;
            case '聚划算':
                return 2;
                break;
            case '淘抢购':
                return 3;
                break;
            default:
                return 1;
                break;

        }
    }
    /**
     * 获取佣金比例.
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

        $url = str_replace('http://', 'https://', $url);

        if (str_contains($url, '/tfscom')) {
            $pos = strpos($url, '/tfscom');
            $domain = substr($url, 0, $pos);
            $url = str_replace($domain, 'https://img.alicdn.com', $url);
        }

        if (starts_with($url, '//')) {
            $url = str_replace('//', 'https://', $url);
        }

        return $url;
    }

    /**
     * 好单库 1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体
     * 设置大淘客分类ID.
     * @param $cat
     * @return int
     */
    private function setDTKCat($cat)
    {
        switch ($cat){
            case 1:  //女装
                return 15;
            case 2: //男装
                return 16;
            case 3: //内衣
                return 17;
            case 4: //美妆
                return 19;
            case 5: //配饰
                return 17;
            case 6: //鞋品
                return 22;
            case 7: //箱包
                return 22;
            case 8: //儿童
                return 19;
            case 9: //母婴
                return 18;
            case 10: //居家
                return 21;
            case 11: //美食
                return 25;
            case 12: //数码
                return 25;
            case 13: //家电
                return 25;
            case 15: //车品
                return 24;
            case 16: //文体
                return 24;
            default:
                return 26;
        }
    }

    /**
     * 设置京推推分类ID.
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
