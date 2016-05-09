<?php

$redis = new Redis();

$redis->connect('redis', 6379);

/* SETUP */
$queue = 'transactions';
$data_for_queue = '{class:"BatchProcessor", transactions: [1,2,3]';
$total_to_push = 5;

/* Push data to the queue */
for ($i = 0; $i < $total_to_push; $i++){
    $pushed = $redis->rpush($queue, $data_for_queue);
    echo "Pushed $pushed\n\r";
    usleep(100000);
}