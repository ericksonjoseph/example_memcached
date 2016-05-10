<?php

require APP_ROOT . 'src/MemqWrapper.php';
require APP_ROOT . 'src/Worker.php';

class MemqWorker extends Worker {

    public $name = 'memq';

    public function __construct()
    {
        $this->driver = new MemqWrapper();
    }

    public function dequeue($queue)
    {
        return $this->driver->dequeue($queue);
    }
}

