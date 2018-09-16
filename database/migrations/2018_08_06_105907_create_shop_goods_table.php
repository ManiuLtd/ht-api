<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopGoodsTable.
 */
class CreateShopGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //商品表
        Schema::create ('shop_goods', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->integer ('merch_id')->nullable (); // 商户
            $table->string ('cates')->nullable (); //多重分类数据集
            $table->integer ('pcate')->nullable (); //一级分类ID
            $table->integer ('ccate')->nullable (); //二级分类ID
            $table->string ('pcates')->nullable (); //一级多重分类ID
            $table->string ('ccates')->nullable (); //二级多重分类ID
            $table->string ('title')->nullable (); //商品名称
            $table->string ('keywords')->nullable (); //关键词
            $table->string ('short_title')->nullable (); //短标题 打印需要
            $table->string ('thumb')->nullable (); //商品图
            $table->string ('unit')->nullable (); //单位
            $table->string ('description')->nullable (); //描述
            $table->text ('content')->nullable (); // 商品详情
            $table->string ('goodssn')->nullable (); // 商品编号
            $table->string ('productsn')->nullable (); // 商品条码
            $table->decimal ('price')->nullable (); //现价
            $table->decimal ('old_price')->nullable (); //原价
            $table->decimal ('cost_price')->nullable (); //成本价
            $table->decimal ('min_price')->nullable (); // 多规格中最小价格，无规格时显示销售价
            $table->decimal ('max_price')->nullable (); // 多规格中最大价格，无规格时显示销售价
            $table->integer ('total')->nullable (); //库存
            $table->tinyInteger ('totalcnf')->nullable (); // 减库存方式 0 拍下减库存 1 付款减库存 2 永不减库存
            $table->integer ('sales')->nullable (); //已售数
            $table->integer ('real_sales')->nullable (); // 实际售出数
            $table->tinyInteger ('show_sales')->nullable ()->default (1); // 显示销量
            $table->tinyInteger ('show_spec')->nullable (); //商品规格设置
            $table->decimal ('weight')->nullable (); //重量
            $table->string ('credit')->nullable (); // 	购买赠送积分，如果带%号，则为按成交价比例计算
            $table->integer ('minbuy')->nullable (); //单次最低购买量
            $table->integer ('maxbuy')->nullable (); //单次最多购买量
            $table->integer ('total_maxbuy')->nullable (); // 用户最多购买量
            $table->tinyInteger ('hasoption')->nullable (); // 启用商品规则 0不启用 1启用
            $table->tinyInteger ('isnew')->nullable (); // 新上
            $table->tinyInteger ('ishot')->nullable (); // 热卖
            $table->tinyInteger ('isrecommand')->nullable (); // 推荐
            $table->tinyInteger ('isdiscount')->nullable (); // 促销
            $table->string ('discount_title')->nullable (); //促销标题
            $table->timestamp ('discount_end')->nullable (); //促销结束时间
            $table->timestamp ('discount_price')->nullable (); //促销价格
            $table->tinyInteger ('issendfree')->nullable (); // 包邮
            $table->tinyInteger ('iscomment')->nullable (); //允许评价
            $table->tinyInteger ('istime')->nullable (); // 限时卖
            $table->timestamp ('time_start')->nullable (); //限卖开始时间
            $table->timestamp ('time_end')->nullable (); //限卖结束时间
            $table->integer ('views')->nullable ()->default (0); //查看次数
            $table->tinyInteger ('hascommission')->nullable ()->default (0); //是否有分销
            $table->decimal ('commission0_rate')->nullable ()->default (0); // 代理商分销比率
            $table->decimal ('commission0_pay')->nullable ()->default (0); // 代理商分销固定佣金
            $table->decimal ('commission1_rate')->nullable ()->default (0); // 一级分销比率
            $table->decimal ('commission1_pay')->nullable ()->default (0); //一级分销固定佣金
            $table->decimal ('commission2_rate')->nullable ()->default (0); //  二级分销比率
            $table->decimal ('commission2_pay')->nullable ()->default (0); //二级分销固定佣金
            $table->decimal ('commission3_rate')->nullable ()->default (0); //  三级分销比率
            $table->decimal ('commission3_pay')->nullable ()->default (0); //三级分销固定佣金
            $table->tinyInteger ('is_not_discount')->nullable (); // 不参与会员折扣
            $table->string ('view_levels')->nullable (); //浏览权限
            $table->string ('buy_levels')->nullable (); //购买权限
            $table->text ('notice_openid')->nullable (); //商家通知
            $table->string ('notice_type')->nullable (); //通知类型
            $table->tinyInteger ('deduct_credit1')->nullable (); //余额抵扣
            $table->tinyInteger ('deduct_credit2')->nullable (); //积分抵扣
            $table->tinyInteger ('ednum')->nullable (); //单品满件包邮 0 : 不支持满件包邮
            $table->tinyInteger ('edmoney')->nullable (); //单品满额包邮 0 : 不支持满额包邮
            $table->integer ('dispatch')->nullable (); // 配送
            $table->tinyInteger ('dispatch_type')->nullable (); //配送类型 0 运费模板 1 统一邮费
            $table->integer ('dispatch_id')->nullable (); //配送ID
            $table->decimal ('dispatch_price')->nullable (); //统一邮费
            $table->string ('province')->nullable (); //商品所在城市 如为空则显示商城所在
            $table->string ('city')->nullable ();  // 商品所在省 如为空则显示商城所在
            $table->string ('tags')->nullable ();  // 标签
            $table->tinyInteger ('show_total')->nullable ();  // 显示库存
            $table->tinyInteger ('auto_receive')->nullable ()->default (0);  // 自动收货
            $table->tinyInteger ('can_not_refund')->nullable ();  // 不允许退货
            $table->tinyInteger ('type')->nullable (); //1 实体物品 2 虚拟物品 3 虚拟物品(卡密)
            $table->tinyInteger ('status')->nullable (); //	状态 0 下架 1 上架
            $table->integer ('sort')->nullable ()->default (0); //排序
            $table->timestamps ();
            $table->softDeletes ();


            $table->index ('user_id');
            $table->index ('member_id');
            $table->index ('merch_id');
            $table->index ('pcate');
            $table->index ('ccate');
            $table->index ('pcates');
            $table->index ('ccates');
            $table->index ('type');
            $table->index ('status');
            $table->index ('created_at');
        });

        //商品参数
        Schema::create ('shop_goods_params', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('goods_id')->nullable (); //产品ID
            $table->string ('title')->nullable (); //标题
            $table->string ('value')->nullable (); //值
            $table->integer ('sort')->nullable (); //排序
            $table->timestamps ();


            $table->index ('user_id');
            $table->index ('goods_id');
        });

        //商品规格
        Schema::create ('shop_goods_options', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('goods_id')->nullable (); //产品ID
            $table->string ('title')->nullable (); //标题
            $table->string ('thumb')->nullable (); //缩略图
            $table->decimal ('price')->nullable (); //商品现价
            $table->decimal ('old_price')->nullable (); //商品原价
            $table->decimal ('cost_price')->nullable (); //商品成本
            $table->integer ('total')->nullable (); // 库存
            $table->decimal ('weight')->nullable (); // 重量
            $table->string ('specs')->nullable (); // 规格
            $table->string ('goodssn')->nullable (); // 商品编号
            $table->string ('productsn')->nullable (); // 商品条码
            $table->integer ('sort')->nullable ()->default (0); // 排序
            $table->timestamps ();

            $table->index ('user_id');
            $table->index ('goods_id');
            $table->index ('title');
            $table->index ('goodssn');
            $table->index ('productsn');
        });


        //商品规格表
        Schema::create ('shop_goods_specs', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('goods_id')->nullable (); //产品ID
            $table->string ('title')->nullable (); //标题
            $table->string ('spec_items')->nullable (); //规格项
            $table->integer ('sort')->nullable (); //排序
            $table->timestamps ();

            $table->index ('user_id');
            $table->index ('goods_id');
            $table->index ('title');
        });

        //商品规格项目表
        Schema::create ('shop_goods_spec_items', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('goods_id')->nullable (); //产品ID
            $table->string ('title')->nullable (); //标题
            $table->string ('thumb')->nullable (); //缩略图
            $table->tinyInteger ('show')->nullable ()->default (1); //1显示 0不显示
            $table->integer ('sort')->nullable (); //排序
            $table->timestamps ();

            $table->index ('user_id');
            $table->index ('goods_id');
            $table->index ('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('shop_goods');
        Schema::drop ('shop_goods_params');
        Schema::drop ('shop_goods_options');
        Schema::drop ('shop_goods_specs');
        Schema::drop ('shop_goods_spec_items');
    }
}
