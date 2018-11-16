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

    /**
     *
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
            $data['tag'] = $tag; //1实时跑单商品，2爆单榜商品，3全部商品，4纯视频单，5聚淘专区
            $data['type'] = 1;
            $data['status'] = 0;
            $data['videoid'] = $result->videoid;
            $data['activity_type'] = $this->GetactivityType($result->activity_type);
            $data['start_time'] = date('Y-m-d H:i:s', $result->couponstarttime);
            $data['end_time'] = date('Y-m-d H:i:s', $result->couponendtime);

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
            $data['start_time'] = Carbon::createFromTimestamp(intval($result->beginTime))->toDateTimeString();
            $data['end_time'] = Carbon::createFromTimestamp(intval($result->endTime))->toDateTimeString();
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $inserts[] = $data;
            //不是全部抓取 更新单条产品
            if ($this->all == 'false') {
                db('tbk_coupons')->updateOrInsert(
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
    protected function  savePDDGoods($results)
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
            $data['start_time'] = Carbon::createFromTimestamp(intval($result->beginTime))->toDateTimeString();
            $data['end_time'] = Carbon::createFromTimestamp(intval($result->endTime))->toDateTimeString();
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            $inserts[] = $data;
            //不是全部抓取 更新单条产品
            if ($this->all == 'false') {
                db('tbk_coupons')->updateOrInsert(
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
     * 好单库 1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体
     * 设置大淘客分类ID.
     * @param $cat
     * @return int
     */
    private function setDTKCat($cat)
    {
        switch ($cat) {
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
//            case 1:  //女装
//                return 15;
            case '鞋靴': //男装
                return 16;
            case '服饰内衣': //内衣配饰
                return 17;
            case '珠宝首饰':
                return 17;
            case '母婴': //母婴玩具
                return 18;
            case '玩具乐器':
                return 18;
            case '个人护理': //美妆个护
                return 19;
            case '美妆护肤':
                return 19;
            case '食品饮料': //食品保健
                return 20;
            case '酒类':
                return 20;
            case '生鲜':
                return 20;
            case '医药保健': //食品保健
                return 20;
            case '厨具': //居家生活
                return 21;
            case '家居日用':
                return 21;
            case '家纺':
                return 21;
            case '汽车用品':
                return 21;
            case '家庭清洁/纸品':
                return 21;
            case '礼品箱包': //鞋品箱包
                return 22;

            case '运动户外': //运动户外
                return 23;
            case '图书': //文体车品
                return 24;
            case '家用电器': //数码家电
                return 25;
            case '手机': //数码家电
                return 25;
            case '数码': //数码家电
                return 25;
            case '电脑、办公':
                return 25;
            default:
                return 26;
        }
    }

    /**
     * 好京客 拼多多分类
     * @param $cat
     * @return int
     */
    protected function setHJKPDDCat($cat)
    {
        switch ($cat) {
            case '美食':
                return 1;
            case '母婴':
                return 4;
            case '水果':
                return 13;
            case '服饰':
                return 14;
            case '百货' :
                return 15;
            case '美妆':
                return 16;
            case '电器':
                return 18;
            case '男装':
                return 743;
            case '家纺':
                return 818;
            case '鞋包':
                return 1281;
            case '运动':
                return 1451;
            case '手机':
                return 1543;
            case '汽车':
                return 2408;
            case '内衣':
                return 1282;
            case '家装':
                return 1917;
            default:
                return 2;
        }
    }

}
