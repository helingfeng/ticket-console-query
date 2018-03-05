<?php

namespace Ticket;

use JonnyW\PhantomJs\Client;

class Browser
{
    protected $client = null;

    protected $timeout = null;

    protected $request = null;

    protected $response = null;

    public function __construct($timeout = 60000, $lazy = true)
    {
        $this->client = Client::getInstance();
        $this->client->getEngine()->setPath('/usr/bin/phantomjs');
        $this->client->getProcedureCompiler()->enableCache();

        $lazy && $this->client->isLazy();
        $this->timeout = $timeout;

        $this->request  = $this->client->getMessageFactory()->createRequest();
        $this->response = $this->client->getMessageFactory()->createResponse();
        $this->request->setTimeout($this->timeout);
    }

    /**
     *  使用 Phantomjs 模拟浏览器GET访问
     * @param string $url
     * @param array $headers
     * @return array
     */
    public function get($url = '', $headers = [])
    {
        empty($headers) || $this->request->setHeaders($headers);
        $this->request->setMethod('GET');
        $this->request->setUrl($url);

        $this->client->send($this->request, $this->response);
        return $this->responseArray();
    }

//    /**
//     * 使用 Phantomjs 模拟浏览器POST访问
//     *
//     * @param string $url
//     * @param array $headers
//     * @param array $data
//     * @return array
//     */
//    public function post($url = '', $headers = [], $data = [])
//    {
//        $request  = $this->client->getMessageFactory()->createRequest();
//        $response = $this->client->getMessageFactory()->createResponse();
//
//        $request->setTimeout($this->timeout);
//        $request->setHeaders($headers);
//        $request->setMethod('POST');
//        $request->setUrl($url);
//        $request->setRequestData($data);
//
//        $this->client->send($request, $response);
//
//        file_put_contents('test',json_encode($request->getBody()));
//        return $this->responseArray($response);
//    }


//    public function captureScreen($url, $width = 293, $height = 190, $top = 0, $left = 0)
//    {
//        $request = $this->client->getMessageFactory()->createCaptureRequest($url, 'GET');
//        $request->setOutputFile(getcwd() . '/public/captcha/' . date('Y-m-d-H-i-s') . '.jpg');
//        $request->setViewportSize($width, $height);
//        $request->setCaptureDimensions($width, $height, $top, $left);
//        $response = $this->client->getMessageFactory()->createResponse();
//        $this->client->send($request, $response);
//        return $this->responseArray($response);
//    }

    protected function responseArray()
    {
        return [
            'http_code' => $this->response->getStatus(),
            'http_time' => $this->response->getTime(),
            'content' => $this->response->getContent(),
            'headers' => $this->response->getHeaders(),
        ];
    }
}