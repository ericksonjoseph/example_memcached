<?php

require APP_ROOT . 'src/Object.php';

abstract class Worker extends Object {

    const DELAY = 1000000;

    public $type = 'worker';

    abstract public function dequeue($queue);

    protected function postProcessDequeuedData(&$data)
    {
        $data = json_decode($data, true);
        $this->log("{$data['_id']}");
        echo print_r(serialize($data), true) . PHP_EOL;
    }

    public function subscribe($queue)
    {
        while (true){
            echo ".\n\r";
            $popped = $this->dequeue($queue);
            usleep(self::DELAY);
        }
    }

}

