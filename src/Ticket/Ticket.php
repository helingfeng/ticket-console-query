<?php

namespace Ticket;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Ticket
{
    protected $seats = [
        '商务座' => '-',
        '特等座' => '-',
        '一等座' => '-',
        '二等座' => '-',
        '高级软卧' => '-',
        '软卧' => '-',
        '硬卧' => '-',
        '软座' => '-',
        '硬座' => '-',
        '无座' => '-',
        '其它' => '-'
    ];

    protected $client = null;

    protected $queryTicketUrl = 'https://kyfw.12306.cn/otn/leftTicket/queryZ';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function queryTickets($from_station, $to_station, $format_date, $purpose_codes = 'ADULT')
    {
        $parameters = [
            'leftTicketDTO.train_date' => $format_date,
            'leftTicketDTO.from_station' => $from_station,
            'leftTicketDTO.to_station' => $to_station,
            'purpose_codes' => $purpose_codes,
        ];
        $request = new Request('GET', $this->queryTicketUrl . '?' . http_build_query($parameters), []);
        $response = $this->client->send($request);
        $code = $response->getStatusCode();
        $body = $response->getBody();

        echo $code;
        echo "\r\n";
        echo $body;
    }

    protected function formatTrainString($string)
    {
        //|23:00-06:00系统维护时间|240000G1010D|G101|VNP|AOH|VNP|AOH|06:43|12:39|05:56|IS_TIME_NOT_BUY|m6x834Y1%2Fdp%2BurLu8dI6uYsKPCOZsDpYJLqIvIE9cY2orkrX|20180219|3|P2|01|11|1|0|||||||||||有|有|7||O0M090|OM9|0

    }
}