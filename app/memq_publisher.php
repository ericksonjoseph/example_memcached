#!/bin/bash
<?php

require '/global/app/bootstrap.php';
require APP_ROOT . 'src/MemqPublisher.php';

$Publisher = new MemqPublisher();

$count = 1;
if (isset($argv[1])){
    $count = $argv[1];
}

$data_for_queue = [
    'class' =>"BatchProcessor",
    'transactions' => [1,2,3]
];

$Publisher->enqueueMany('transactions', $data_for_queue, $count);
