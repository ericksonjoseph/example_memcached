<?php

require '/global/app/bootstrap.php';
require APP_ROOT . 'src/RedisWorkerCluster.php';

$redis = new RedisWorkerCluster();


write('all Redis Keys:', $redis->driver->keys('*'));
write('transactions queue contents:', $redis->driver->lrange('transactions', 0, -1));

if (isset($_GET['delete'])){
    write('flushall:', $redis->flushall());
    write('all Redis Keys:', $redis->driver->keys('*'));
    write('transactions queue contents:', $redis->driver->lrange('transactions', 0, -1));
}

function write($prepend, $s){

    if (is_array($s)){
        echo "<pre>" . $prepend . print_r($s, true) . "</pre><br>";
        return;
    }
    echo $prepend . ' ' . $s;
}

