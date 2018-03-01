<?php
/**
 * User: helingfeng
 */

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ticket\Stations;
use Ticket\Ticket;
use Ticket\Train;

class TicketQueryCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            // 命令的名字（"bin/console" 后面的部分）
            ->setName('ticket:quick-query')
            // the short description shown while running "php bin/console list"
            // 运行 "php bin/console list" 时的简短描述
            ->setDescription('查询12306列车余票信息.')
            // the full command description shown when running the command with
            // the "--help" option
            // 运行命令时使用 "--help" 选项时的完整命令描述
            ->setHelp("This command allows you to query ticket...")
            // from station
            ->addArgument('from_station', InputArgument::REQUIRED, '余票列车起点车站?')
            // from station
            ->addArgument('to_station', InputArgument::REQUIRED, '余票列车终点车站?')
            // date
            ->addArgument('date', InputArgument::REQUIRED, '余票查询日期[Y-m-d]?')
            // purpose codes
            ->addOption('code', null, InputOption::VALUE_REQUIRED, '车票类型，普通票/学生票?', '普通票');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $from_station = $input->getArgument('from_station');
        $to_station = $input->getArgument('to_station');
        $date = $input->getArgument('date');
        $code = $input->getOption('code');

        // parameter
        if (array_search($code, Train::$purposeCodes) === false) {
            $output->writeln('车票类型不合法 --code=普通票/学生票.');
            exit;
        }

        $station = new Stations();
        if (!$station->isExist($from_station)) {
            $output->writeln('查询起点车站名称不存在.');
            exit;
        }
        if (!$station->isExist($to_station)) {
            $output->writeln('查询终点车站名称不存在.');
            exit;
        }

        if (!$station->checkDateValid($date)) {
            $output->writeln('查询日期格式不正确.');
            exit;
        }

        $ticket = new Ticket();
        $trains = $ticket->queryTickets($station->stationName2Code($from_station), $station->stationName2Code($to_station), $date, array_search($code, Train::$purposeCodes));

        $t = [];
        foreach ($trains as $train) {
            $seat = [];
            $seat['station_code'] = $train['queryLeftNewDTO']['station_train_code'];
            $seat['gr_num'] = $train['queryLeftNewDTO']['gr_num'];
            $seat['qt_num'] = $train['queryLeftNewDTO']['qt_num'];
            $seat['rw_num'] = $train['queryLeftNewDTO']['rw_num'];
            $seat['rz_num'] = $train['queryLeftNewDTO']['rz_num'];
            $seat['tz_num'] = $train['queryLeftNewDTO']['tz_num'];
            $seat['wz_num'] = $train['queryLeftNewDTO']['wz_num'];
            $seat['yw_num'] = $train['queryLeftNewDTO']['yw_num'];
            $seat['yz_num'] = $train['queryLeftNewDTO']['yz_num'];
            $seat['ze_num'] = $train['queryLeftNewDTO']['ze_num'];
            $seat['zy_num'] = $train['queryLeftNewDTO']['zy_num'];
            $seat['swz_num'] = $train['queryLeftNewDTO']['swz_num'];

            array_push($t, $seat);
        }

        $heading = [];
        if (!empty($t[0])) {
            foreach (array_keys($t[0]) as $item) {
                if (array_key_exists($item, Train::$seatsMapping)) {
                    array_push($heading, Train::$seatsMapping[$item]);
                } else {
                    array_push($heading, $item);
                }
            }
        }
        $table = new Table($output);
        $table->setHeaders($heading)->setRows($t);
        $table->render();

    }
}