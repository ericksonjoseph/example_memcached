<?php

require APP_ROOT . 'src/Publisher.php';
require APP_ROOT . 'src/MemqWrapper.php';

class MemqPublisher extends Publisher {

    public $name = 'memq';

    public function __construct()
    {
        $this->driver = new MemqWrapper();
    }

    public function enqueue($queue, $data)
    {
        return $this->driver->enqueue($queue, $data);
    }
}

