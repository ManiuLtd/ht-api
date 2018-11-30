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
     * 1实时跑单商品，2爆单榜商品，3全部商品，4纯视频单，5聚淘专区.
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
                break;
            case 'timingItems':
                $this->saveTimeGoods($this->results);
                break;
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
            $data['title'] = $result->itemshorttitle;
//            $data['short_title'] = $result->D_title;
            $data['cat'] = $this->setTBCat($result->fqcat);
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
            $data['tag'] = $tag; //1实时跑单商品，2爆单榜商品，3全部商品，4纯视频单，5聚淘专区
            $data['type'] = 1;
            $data['status'] = 0;
            $data['videoid'] = $result->videoid;
            $data['activity_type'] = $this->GetactivityType($result->activity_type);
            $data['start_time'] = isset($result->couponstarttime) ?  date('Y-m-d H:i:s', $result->couponstarttime) : null;
            $data['end_time'] = isset($result->couponendtime) ? date('Y-m-d H:i:s', $result->couponendtime) : null;

            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $data['status'] = 0;
            $inserts[] = $data;
            //不是全部抓取 更新单条产品
            if ($this->all == 'false') {
                $data['status'] = 1;
                $coupon->updateOrCreate(
                    ['item_id' => $data['item_id'], 'type' => 1],
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
     * 好京客.
     * @param $results
     */
    protected function saveJTTGoods($results)
    {
        $inserts = [];
        foreach ($results as $result) {
            $data['title'] = $result->skuName;
            $data['cat'] = $this->setJTTCat($result->cname);
            $data['pic_url'] = $result->picUrl;
            $data['item_id'] = $result->skuId;
            $data['item_url'] = $result->materiaUrl;
            $data['price'] = $result->wlPrice;
            $data['final_price'] = $result->wlPrice_after;
            $data['coupon_price'] = $result->discount;
            $data['coupon_link'] = $result->couponList;
            $data['commission_rate'] = $result->wlCommissionShare;
            $data['introduce'] = $result->skuDesc;
            $data['type'] = 2;
            $data['status'] = 0;
            $data['start_time'] = isset($result->beginTime) ? Carbon::createFromTimestamp(intval($result->beginTime+1))->toDateTimeString() : null;
            $data['end_time'] = isset($result->endTime) ? Carbon::createFromTimestamp(intval($result->endTime))->toDateTimeString() : null;
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $data['status'] = 0;
            $inserts[] = $data;
            //不是全部抓取 更新单条产品
            if ($this->all == 'false') {
                $data['status'] = 1;
                db('tbk_coupons')->updateOrInsert(
                    ['item_id' => $data['item_id'], 'type' => 2],
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
        $inserts = [];
        foreach ($results as $result) {
            $data['title'] = $result->skuName;
            $data['cat'] = $this->setHJKPDDCat($result->cname);
            $data['pic_url'] = $result->picUrl;
            $data['item_id'] = $result->skuId;
            $data['item_url'] = $result->materiaUrl;
            $data['volume'] = $result->sales;
            $data['price'] = $result->wlPrice;
            $data['final_price'] = $result->wlPrice_after;
            $data['coupon_price'] = $result->discount;
            $data['commission_rate'] = $result->wlCommissionShare;
            $data['introduce'] = $result->skuDesc;
            $data['total_num'] = $result->coupon_total_quantity;
            $data['receive_num'] = $result->coupon_total_quantity - $result->coupon_remain_quantity;
            $data['type'] = 3;
            $data['status'] = 0;
            $data['start_time'] = isset($result->beginTime) ? Carbon::createFromTimestamp(intval($result->beginTime))->toDateTimeString() : null;
            $data['end_time'] = isset($result->endTime) ? Carbon::createFromTimestamp(intval($result->endTime))->toDateTimeString() : null;
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $data['status'] = 0;
            $inserts[] = $data;
            //不是全部抓取 更新单条产品
            if ($this->all == 'false') {
                $data['status'] = 1;
                db('tbk_coupons')->updateOrInsert(
                    ['item_id' => $data['item_id'], 'type' => 3],
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
     * 定时拉取.
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
            $data['receive_num'] = $result->couponreceive2; //当天领取数量
            $data['type'] = 1;
            $data['status'] = 0;
            $data['videoid'] = $result->videoid;
            $data['activity_type'] = $this->GetactivityType($result->activity_type);
            $data['start_time'] = $result->couponstarttime ? date('Y-m-d H:i:s', $result->couponstarttime) : '';
            $data['end_time'] = $result->couponendtime ? date('Y-m-d H:i:s', $result->couponendtime) : '';
            $data['starttime'] = $result->start_time ? date('Y-m-d H:i:s', $result->start_time) : '';
            $data['endtime'] = $result->end_time ? date('Y-m-d H:i:s', $result->end_time) : '';
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
     * 活动类型：.
     * @param $activity_type
     * @return int
     */
    protected function GetactivityType($activity_type)
    {
        switch ($activity_type) {
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
     * @param $cat
     * @return int
     */
    protected function setTBCat($cat)
    {
        switch ($cat){
            case 1:
                return 43;
            case 2:
                return 44;
            case 3:
                return 45;
            case 4:
                return 45;
            case 5:
                return 47;
            case 6:
                return 48;
            case 7:
                return 49;
            case 8:
                return 50;
            case 9:
                return 51;
            case 10:
                return 52;
            case 11:
                return 53;
            case 12:
                return 54;
            case 13:
                return 55;
            case 15:
                return 56;
            case 16:
                return 57;
            case 17:
                return 58;
            case 14:
                return 59;
            default:
                return 59;
        }
    }
    /**
     * 设置好京客京东分类ID.
     * @param $cat
     * @return int
     */
    private function setJTTCat($cat)
    {
        switch ($cat) {
            case '个人护理':
                return 1;
            case '农资绿植':
                return 2;
            case '医药保健':
                return 3;
            case '厨具':
                return 4;
            case '图书':
                return 5;
            case '宠物生活':
                return 6;
            case '家具':
                return 7;
            case '家居日用':
                return 8;
            case '家庭清洁/纸品':
                return 9;
            case '家用电器':
                return 10;
            case '家纺':
                return 11;
            case '手机':
                return 12;
            case '教育培训':
                return 13;
            case '数码':
                return 14;
            case '服饰内衣':
                return 15;
            case '母婴':
                return 16;
            case '汽车用品':
                return 17;
            case '玩具乐器':
                return 18;

            case '珠宝首饰':
                return 19;
            case '生鲜':
                return 20;
            case '电脑、办公':
                return 21;
            case '礼品箱包':
                return 22;
            case '美妆护肤':
                return 23;
            case '运动户外':
                return 24;
            case '酒类':
                return 25;
            case '鞋靴':
                return 26;
            case '食品饮料':
                return 27;
            default:
                return 60;
        }
    }

    /**
     * 好京客 拼多多分类.
     * @param $cat
     * @return int
     */
    protected function setHJKPDDCat($cat)
    {
        switch ($cat) {
            case '美食':
                return 28;
            case '母婴':
                return 29;
            case '水果':
                return 30;
            case '服饰':
                return 31;
            case '百货':
                return 32;
            case '美妆':
                return 33;
            case '电器':
                return 34;
            case '男装':
                return 35;
            case '家纺':
                return 36;
            case '鞋包':
                return 37;
            case '运动':
                return 38;
            case '手机':
                return 39;
            case '汽车':
                return 42;
            case '内衣':
                return 40;
            case '家装':
                return 41;
            default:
                return 61;
        }
    }
}
