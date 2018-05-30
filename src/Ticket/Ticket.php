<?php

namespace Ticket;

class Ticket
{

    protected $queryTicketUrl = 'https://kyfw.12306.cn/otn/leftTicket/query';

    public function queryTickets($from_station, $to_station, $format_date, $purpose_codes = 'ADULT')
    {
        $parameters = [
            'leftTicketDTO.train_date' => $format_date,
            'leftTicketDTO.from_station' => $from_station,
            'leftTicketDTO.to_station' => $to_station,
            'purpose_codes' => $purpose_codes,
        ];
        $headers = [
            'Referer' => 'https://kyfw.12306.cn/otn/leftTicket/init'
        ];
        $url = $this->queryTicketUrl . '?' . http_build_query($parameters);
        $response = $this->curl($url, 'GET', [], $headers);
        // 接口调用成功
        if ($response['httpCode'] == 200) {
            $result = json_decode($response['response'], true);
            $train_str_arr = $result['data']['result'];
            $trains = [];
            if (is_array($train_str_arr)) {
                foreach ($train_str_arr as $train_str) {
                    array_push($trains, $this->formatTrainString($train_str));
                }
            }
            return $trains;
        } else {
            throw new \RuntimeException('查询接口异常.');
        }
    }

    /**
     * 格式化字符串转对象
     *
     * @param $string
     * @return array
     */
    protected function formatTrainString($string)
    {
        //|23:00-06:00系统维护时间|240000G1010D|G101|VNP|AOH|VNP|AOH|06:43|12:39|05:56|IS_TIME_NOT_BUY|m6x834Y1%2Fdp%2BurLu8dI6uYsKPCOZsDpYJLqIvIE9cY2orkrX
        //|20180219|3|P2|01|11|1|0|||||||||||有|有|7||O0M090|OM9|0
        $arr = explode("|", $string);

        $data = array();
        $data["secretStr"] = $arr[0];
        $data["buttonTextInfo"] = $arr[1];

        $train = array();
        $train['train_no'] = $arr[2];
        $train['station_train_code'] = $arr[3];
        $train['start_station_telecode'] = $arr[4];
        $train['end_station_telecode'] = $arr[5];
        $train['from_station_telecode'] = $arr[6];
        $train['to_station_telecode'] = $arr[7];
        $train['start_time'] = $arr[8];
        $train['arrive_time'] = $arr[9];
        $train['lishi'] = $arr[10];
        $train['canWebBuy'] = $arr[11];
        $train['yp_info'] = $arr[12];
        $train['start_train_date'] = $arr[13];
        $train['train_seat_feature'] = $arr[14];
        $train['location_code'] = $arr[15];
        $train['from_station_no'] = $arr[16];
        $train['to_station_no'] = $arr[17];
        $train['is_support_card'] = $arr[18];
        $train['controlled_train_flag'] = $arr[19];
        $train['gg_num'] = $arr[20] ?: "--";
        $train['gr_num'] = $arr[21] ?: "--";
        $train['qt_num'] = $arr[22] ?: "--";
        $train['rw_num'] = $arr[23] ?: "--";
        $train['rz_num'] = $arr[24] ?: "--";
        $train['tz_num'] = $arr[25] ?: "--";
        $train['wz_num'] = $arr[26] ?: "--";
        $train['yb_num'] = $arr[27] ?: "--";
        $train['yw_num'] = $arr[28] ?: "--";
        $train['yz_num'] = $arr[29] ?: "--";
        $train['ze_num'] = $arr[30] ?: "--";
        $train['zy_num'] = $arr[31] ?: "--";
        $train['swz_num'] = $arr[32] ?: "--";
        $train['srrb_num'] = $arr[33] ?: "--";
        $train['yp_ex'] = $arr[34];
        $train['seat_types'] = $arr[35];

        $stations = new Stations();
        $train['from_station_name'] = $stations->stationCode2Name($arr[6]);
        $train['to_station_name'] = $stations->stationCode2Name($arr[7]);

        $data['queryLeftNewDTO'] = $train;
        return $data;
    }

    public function curl($url, $method = 'GET', $params = [], $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);

        // 不校验证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);

        return ['httpCode' => $httpCode, 'response' => $response, 'httpTime' => $httpTime];
    }
}