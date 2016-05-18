<?php

require APP_ROOT . 'src/Worker.php';

class RedisWorker extends Worker {

    public $name = 'redis';

    public function __construct()
    {
        $this->driver = new Redis();
        $this->driver->pconnect('127.0.0.1', 6379);
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

