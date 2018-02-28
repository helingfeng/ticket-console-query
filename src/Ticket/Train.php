<?php

namespace Ticket;


class Train
{
    /**
     * 座位映射
     * @var array
     */
    public static $seatsMapping = [
        'swz_num' => '商务座',
        'tz_num' => '特等座',
        'zy_num' => '一等座',
        'ze_num' => '二等座',
        'gr_num' => '高级软卧',
        'rw_num' => '软卧',
        'yw_num' => '硬卧',
        'rz_num' => '软座',
        'yz_num' => '硬座',
        'wz_num' => '无座',
        'qt_num' => '其它'
    ];

    /**
     * 车票类型映射
     * @var array
     */
    public static $purposeCodes = [
        'ADULT' => '普通票',
        '0X00' => '学生票'
    ];

}