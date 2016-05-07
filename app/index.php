<?php

// Required env variables for memq
define('MEMQ_POOL', 'cache:11211');
define('MEMQ_TTL', 0);

require dirname(__FILE__) . '/src/Memq.php';
require dirname(__FILE__) . '/src/MemcachedCLI.php';

$x = MEMQ::enqueue('transactions', '{class:"BatchProcessor", transactions: [1,2,3]');

$mc = new Memcached();
$cli = new MemcachedCLI("cache", 11211);

$mc->addServer("cache", 11211);

write('flush = ' , $mc->flush());
write('set key_1 = ' , $mc->set('key_1', 'value_1'));
write('set key_2 = ' , $mc->set('key_2', 'value_2'));
write('get key_1 = ' , $mc->get('key_1'));
write('get stats = ' , $cli->getAllKeys());


function write($prepend, $msg){

    if (is_array($msg)){
        echo $prepend;
        echo '<pre>' . print_r($msg, true) . '</pre>';
        return;
    }
    echo "$prepend $msg<br>";
}
