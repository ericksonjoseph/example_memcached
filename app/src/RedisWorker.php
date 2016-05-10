<?php

require APP_ROOT . 'src/Worker.php';

class RedisWorker extends Worker {

    public $name = 'redis';

    public function __construct()
    {
        $this->driver = new Redis();
        $this->driver->connect('redis', 6379);
    }

    public function dequeue($queue)
    {
        $data = $this->driver->lpop($queue);
        if ($data){
            $this->postProcessDequeuedData($data);
        }
        return $data;
    }
}

