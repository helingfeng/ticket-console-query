<?php
/**
 * User: helingfeng
 */

namespace Command;

use JonnyW\PhantomJs\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DomCrawler\Crawler;
use Ticket\Browser;
use Ticket\Stations;
use Ticket\Ticket;
use Ticket\Train;

class GetPassengersCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            // 命令的名字（"bin/console" 后面的部分）
            ->setName('ticket:get-passengers')
            // the short description shown while running "php bin/console list"
            // 运行 "php bin/console list" 时的简短描述
            ->setDescription('获取该账号的乘客列表.')
            // the full command description shown when running the command with
            // the "--help" option
            // 运行命令时使用 "--help" 选项时的完整命令描述
            ->setHelp("This command allows you to get passengers...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ticket = new Ticket();

        $helper = $this->getHelper('question');
        $question = new Question('请输入验证码答案:', '1');
        $question->setValidator(function ($answer) use ($ticket, $output) {
            $result = $ticket->checkCaptcha(explode(',', $answer));
            $output->writeln('接口返回:' . $result['result_message']);
            if ($result['result_code'] != 4) {
                $ticket->generateCaptcha();
                throw new \RuntimeException('验证码不正确');
            }
            return $answer;
        });
        $question->setMaxAttempts(10);

        $ticket->generateCaptcha();
        $helper->ask($input, $output, $question);

        $result = $ticket->webLogin();
        $output->writeln('登录接口返回:' . json_encode($result, JSON_UNESCAPED_UNICODE));

        $ticket->webLogout();

        $output->writeln('进行回调...');
        $response = $ticket->userLogin();
        $output->writeln('登录回调状态：' . $response['http_code']);


        file_put_contents('login',$response['content']);

        $output->writeln('正在获取乘客信息...');
        $passengers_html = $ticket->getPassengers();

        file_put_contents('passenger',$passengers_html);

        $crawler = new Crawler($passengers_html);
        $crawler = $crawler->filter('body > pre');


        $passengers = json_decode($crawler->text(),true);
        $passengers = $passengers['data']['normal_passengers'];


        if(!empty($passengers)){
            $output->writeln('乘客信息列表:');
            $heading = ['passenger_name','sex_name','mobile_no','born_date','passenger_id_no','passenger_type_name'];
            $table = new Table($output);
            $rows = [];
            foreach ($passengers as $passenger){
                $rows[] = [
                    $passenger['passenger_name'],
                    $passenger['sex_name'],
                    $passenger['mobile_no'],
                    $passenger['born_date'],
                    $passenger['passenger_id_no'],
                    $passenger['passenger_type_name'],
                ];
            }
            $table->setHeaders($heading)->setRows($rows);
            $table->render();
        }else{
            $output->writeln('无法获取乘客信息或为空.');
        }

        $output->writeln('正在退出...');
        $output->writeln('完成登出.');
    }
}