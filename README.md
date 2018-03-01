## 12306 车票助手

TicketHelper.

基于`PHP`开发一个12306车票助手程序.


### Blog

博客地址: https://www.helingfeng.com

- [https://www.helingfeng.com/2018/02/18/12306-%E4%BD%99%E7%A5%A8%E6%9F%A5%E8%AF%A2/](https://www.helingfeng.com/2018/02/18/12306-%E4%BD%99%E7%A5%A8%E6%9F%A5%E8%AF%A2/ "https://www.helingfeng.com/2018/02/18/12306-%E4%BD%99%E7%A5%A8%E6%9F%A5%E8%AF%A2/")
- [https://www.helingfeng.com/2018/02/19/12306-%E9%AA%8C%E8%AF%81%E7%99%BB%E5%BD%95/](https://www.helingfeng.com/2018/02/19/12306-%E9%AA%8C%E8%AF%81%E7%99%BB%E5%BD%95/ "https://www.helingfeng.com/2018/02/19/12306-%E9%AA%8C%E8%AF%81%E7%99%BB%E5%BD%95/")

---


### Feature

- 余票查询 
- 验证登录
- 订单查询（开发中）
- 账号客户信息获取（开发中）
- 配置抢票任务（规划中）

### Installation

环境要求

- PHP7
- Composer

依赖包安装

```shell
composer install
```

### Usage

```shell
php bin/console

输出
Available commands:
  help                Displays help for a command
  list                Lists commands
 ticket
  ticket:quick-query  查询12306列车余票信息.
```

余票查询命令

```shell
php bin/console ticket:quick-query 北京 上海 2018-02-28
```
![](https://www.helingfeng.com/wp-content/uploads/2018/03/Selection_042.png)

余票查询帮助

```shell
Arguments:
  from_station          余票列车起点车站?
  to_station            余票列车终点车站?
  date                  余票查询日期[Y-m-d]?

Options:
      --code=CODE       车票类型，普通票/学生票? [default: "普通票"]

```

...

### License

This library is published under The MIT License.

### Thanks

软件作者：何凌枫（helingfeng）
