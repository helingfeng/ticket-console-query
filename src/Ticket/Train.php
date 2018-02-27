<?php

namespace Ticket;


class Train
{
    protected $code = null;
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

    public function __construct($code = '', $seats = [])
    {
        $this->code = $code;
        $this->seats = array_merge($this->seats,$seats);
    }

    public function getSeats()
    {
        return $this->seats;
    }
}