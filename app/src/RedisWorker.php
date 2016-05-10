<?php

require APP_ROOT . 'src/Worker.php';

class RedisWorker extends Worker {

    public function __construct()
    {
        $this->driver = new Redis();
        $this->driver->connect('redis', 6379);
    }

    public function dequeue($queue)
    {
        return $this->driver->lpop($queue);
    }
}

