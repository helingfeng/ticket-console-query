<?php

require_once './vendor/autoload.php';

$ticket = new Ticket\Ticket();

$ticket->queryTickets('BJP','SHH','2018-02-28');