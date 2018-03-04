<?php

require_once './vendor/autoload.php';


$client = \JonnyW\PhantomJs\Client::getInstance();
$client->getEngine()->setPath('/usr/bin/phantomjs');
$client->isLazy(); // 让客户端等待所有资源加载完毕

$request = $client->getMessageFactory()->createRequest();
$request->setTimeout(10000); // 设置超时时间(超过这个时间停止加载并渲染输出画面)
$request->setHeaders(['Cookie'=>'JSESSIONID=BD4DCDF24A14505FC5EA9F54969B6B21']);

$response = $client->getMessageFactory()->createResponse();
$request->setMethod('GET');
$request->setUrl('https://kyfw.12306.cn/otn/confirmPassenger/getPassengerDTOs');
//$request->setUrl('https://kyfw.12306.cn/otn/passengers/init');
$client->send($request, $response);

echo $response->getContent();