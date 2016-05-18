<?php

require APP_ROOT . 'src/Publisher.php';

class RedisPublisher extends Publisher {

    public $name = 'redis';

    public function __construct()
    {
        $this->driver = new Redis();
        $this->driver->pconnect('127.0.0.1', 6379);
    }

    public function enqueue($queue, $data)
    {
        parent::enqueue($queue, $data);
        return $this->driver->rpush($queue, $data);
    }
}

