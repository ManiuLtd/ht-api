## 项目说明

基于laravel最新版的多商户商城+三合一淘宝客

## 源码保密

公司内部项目，源码禁止复制，禁止泄露，禁止分享给不在项目组的其他人，否则后果自负！

## 开发注意

1. 默认只有控制器命名使用复数，其他模块不要使用复数。

2. 数据库统一由我来创建migration,你们git pull之后执行php artisan migrate就行了

3. 每个人负责的模块写完之后，记得更新接口文档和雨雀的数据库字典

## 资料

接口文档地址：https://documenter.getpostman.com/view/4461870/RWThV1uB

使用文档地址：https://www.yuque.com/hongtang/api/agmgft

内网映射工具：https://open-doc.dingtalk.com/microapp/debug/ucof2g

微信测试号申请： https://mp.weixin.qq.com/debug/cgi-bin/sandboxinfo?action=showinfo&t=sandbox/index

## 环境要求

1. PHP >= 7.1.3
2. **[Composer](https://getcomposer.org/)**
3. PHP openssl 扩展
4. PHP fileinfo 扩展

## 安装
```bash
1. git clone https://git.coding.net/cnsecer/htshop-api.git

2. composer update 

3. 修改.env并配置数据库信息

4. php artisan migrate && php artisan db:seed


```
