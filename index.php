<?php

require_once './vendor/autoload.php';

$ticket = new Ticket\Ticket();

$trains = $ticket->queryTickets('BJP','SHH','2018-02-28');

if($trains === false){
    echo "接口异常.请重新再试!\r\n";
    exit;
}

$t = [];
foreach ($trains as $train){
    array_push($t,$train['queryLeftNewDTO']);
}

$renderer = new \MathieuViossat\Util\ArrayToTextTable($t);
echo $renderer->getTable();