<?php

require APP_ROOT . 'src/Object.php';

abstract class Worker extends Object {

    const DELAY = 0;

    public $name = 'abstract';

    public $type = 'worker';

    abstract public function dequeue($queue);

    public function subscribe($queue)
    {
        while (true){
            //echo ".\n\r";
            $popped = $this->dequeue($queue);
            if ($popped){
                echo print_r($popped, true) . PHP_EOL;
            }
            usleep(self::DELAY);
        }
    }

}

