<?php

require APP_ROOT . 'src/Worker.php';

class RedisWorker extends Worker {

    public $name = 'redis';

    public function __construct()
    {
        $this->setupRedisDriver();
    }

    public function dequeue($queue)
    {
        $data = $this->driver->lpop($queue);
        if ($data){
            $this->postProcessDequeuedData($data);
        }
        return $data;
    }

    public function flushall()
    {
        $this->driver->flushall();
    }

    /**
     * Setup redis driver
     *
     * @access private
     * @return void
     */
    private function setupRedisDriver()
    {
        $this->driver = new Redis();
        $this->driver->connect('127.0.0.1', 6379);
    }
}

