<?php
/**
 * User: helingfeng
 */

namespace Command;

use MathieuViossat\Util\ArrayToTextTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
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
            $output->writeln('接口返回:'.$result['result_message']);
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
        $output->writeln('接口返回:'.json_encode($result,JSON_UNESCAPED_UNICODE));

//        if ($result['result_code'] == 4) {
//            // ...
//
//        }

        while ($ticket->getPassengers() == false){

            $question = new ConfirmationQuestion('网络频繁，是否继续访问？');
            $helper->ask($input, $output, $question);
        }

    }
}