<?php

require_once '/global/app/bootstrap.php';

// Required env variables for memq
define('MEMQ_POOL', 'cache:11211');
define('MEMQ_TTL', 0);

require dirname(__FILE__) . '/Memq.php';
require dirname(__FILE__) . '/MemcachedCLI.php';

class MemqWrapper {

    public function __construct()
    {
        $this->cli = new MemcachedCLI("cache", 11211);
        $this->mc = new Memcached();
        $this->mc->addServer("cache", 11211);
    }

    public function flush()
    {
        $this->mc->flush();
        $all_keys = $this->cli->getAllKeys();
        
        $this->debug('delete all keys:', $this->mc->deleteMulti($all_keys));
        $this->debug('flushed. Remaining keys:', $this->cli->getAllKeys());
    }

    public function enqueue($queue, $data)
    {
        return MEMQ::enqueue($queue, $data);
    }

    public function dequeue($queue)
    {
        if (MEMQ::is_empty($queue)){
            //$this->log("The queue $queue is empty");
            return false;
        }

        return MEMQ::dequeue($queue);
    }

    public function debug($prepend, $msg = '')
    {
        if (is_array($msg)){
            echo $prepend;
            echo '<pre>' . print_r($msg, true) . '</pre>';
            return;
        }
        echo "$prepend $msg<br>";
    }

    private function log($message)
    {
        if (!is_string($message)){
            throw new \Exception('Log message must be of type string');
        }

        $msg = "$message\n\r";

        file_put_contents(LOG_DIR . 'memq.wrapper.log', $msg, FILE_APPEND);
    }
}
