<?php

require APP_ROOT . 'src/Publisher.php';

class RedisPublisher extends Publisher {

    public $name = 'redis';

    public function __construct()
    {
        $this->setupRedisDriverCluster();
    }

    public function enqueue($queue, $data)
    {
        parent::enqueue($queue, $data);
        return $this->driver->rpush($queue, $data);
    }

    private function setupRedisDriver()
    {
        $this->driver = new Redis();
        $this->driver->connect('127.0.0.1', 6379);
    }

    private function setupRedisDriverCluster()
    {
        $cluster = array('127.0.0.1:7000','127.0.0.1:7001','127.0.0.1:7002','127.0.0.1:7003','127.0.0.1:7004','127.0.0.1:7005');
        $this->driver = new RedisCluster(NULL, $cluster);
    }
}
