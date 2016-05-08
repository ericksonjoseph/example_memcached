<?php

// Required env variables for memq
define('MEMQ_POOL', 'cache:11211');
define('MEMQ_TTL', 0);

/* Dependencies */
require dirname(__FILE__) . '/src/Memq.php';
require dirname(__FILE__) . '/src/MemcachedCLI.php';

/* Connect to memcached */
$cli = new MemcachedCLI("cache", 11211);
$mc = new Memcached();
$mc->addServer("cache", 11211);

/* Clean queue */
/* this will empty all values in all keys but not the keys themselves */
if (isset($_GET['delete'])){
    echo 'deleting...<br>';
    //$cli::debug('flush:', $mc->flush());
    /* this will empty all keys AND all values */
    $cli::debug('delete all keys:', $mc->deleteMulti($cli->getAllKeys()));
}

/* Get All Keys */
$cli::debug('All Keys:', $cli->getAllKeys());
