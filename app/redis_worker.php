<?php

/* Connect to memcached */
$redis = new Redis();
$redis->connect('redis', 6379);

/* SETUP */
$queue = 'transactions';

/* Get data from the queue */
while (true){
    echo ".\n\r";
    $popped = $redis->blpop($queue, 0);
    //$popped = $redis->lpop($queue);
    if ($popped){
        echo print_r($popped, true) . "\n\r";
    }
    usleep(100000);
}
