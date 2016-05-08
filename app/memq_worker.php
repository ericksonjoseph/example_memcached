<?php

/* Dependencies */
require dirname(__FILE__) . '/src/MemqWrapper.php';

/* Connect to memcached */
$memq = new MemqWrapper();

/* SETUP */
$queue = 'transactions';

/* Get data from the queue */
while (true){
    $popped = $memq->dequeue($queue);
    if ($popped){
        echo print_r($popped, true) . "\n\r";
    }
    usleep(1000);
}
