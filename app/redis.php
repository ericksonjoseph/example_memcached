<?php

$redis = new \Redis();
$redis->connect('redis', 6379);


write('all Redis Keys:', $redis->keys('*'));
write('transactions queue contents:', $redis->lrange('transactions', 0, -1));

if (isset($_GET['delete'])){
    write('flushall:', $redis->flushall());
    write('all Redis Keys:', $redis->keys('*'));
    write('transactions queue contents:', $redis->lrange('transactions', 0, -1));
}

function write($prepend, $s){

    if (is_array($s)){
        echo "<pre>" . $prepend . print_r($s, true) . "</pre><br>";
        return;
    }
    echo $prepend . ' ' . $s;
}

