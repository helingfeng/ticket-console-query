<?php

namespace Ticket;


class Stations
{
    protected $stations = [];

    public function __construct()
    {
        $this->init();
    }

    public function stations()
    {
        return $this->stations;
    }

    public function isExist($station_name)
    {
        return array_search($station_name, $this->stations) !== false;
    }

    public function stationCode2Name($station_code)
    {
        if (array_key_exists($station_code, $this->stations)) {
            return $this->stations[$station_code];
        } else {
            return '未知车站编号';
        }
    }

    public function stationName2Code($station_name)
    {
        if (array_search($station_name, $this->stations) !== false) {
            return array_search($station_name, $this->stations);
        } else {
            return '未知车站名称';
        }
    }

    protected function init()
    {
        $stations_name_string = file_get_contents(__DIR__ . '/stations.data');
        // @bjb|北京北|VAP|beijingbei|bjb
        $stations_name_arr = explode('|', $stations_name_string);
        $num = count($stations_name_arr);
        for ($index = 0; $index < $num; $index += 5) {
            if (isset($stations_name_arr[$index + 1]) && isset($stations_name_arr[$index + 2])) {
                $this->stations[$stations_name_arr[$index + 2]] = $stations_name_arr[$index + 1];
            }
        }
    }

    public function checkDateValid($date, $formats = array("Y-m-d"))
    {
        $unix_time = strtotime($date);
        if (!$unix_time || (strtotime(date('Y-m-d'))) > $unix_time) {
            return false;
        }

        foreach ($formats as $format) {
            if (date($format, $unix_time) == $date) {
                return true;
            }
        }
        return false;
    }

}