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
- 账号客户信息获取
- 配置抢票任务（规划中）

### Installation

环境要求

- PHP7
- Composer

依赖包安装

```shell
composer install
```

```shell
cp auth.json.example auth.json

# 修改配置文件
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

##### 余票查询命令

```shell
php bin/console ticket:quick-query 北京 上海 2018-02-28
```
![](https://www.helingfeng.com/wp-content/uploads/2018/03/Selection_042.png)

参数说明

```shell
 php bin/console ticket:quick-query --help
```
![](https://www.helingfeng.com/wp-content/uploads/2018/03/Selection_044.png)

...


##### 乘客信息查询

```shell
php bin/console ticket:get-passengers


请输入验证码答案:1
接口返回:验证码校验成功
登录接口返回:登录成功
进行回调...
登录回调状态：200
正在获取乘客信息...
乘客信息列表:
```
![](https://www.helingfeng.com/wp-content/uploads/2018/03/Selection_045.png)


### License

This library is published under The MIT License.

### Thanks

软件作者：何凌枫（helingfeng）
