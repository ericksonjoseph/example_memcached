<?php

require APP_ROOT . 'src/Object.php';

abstract class Publisher extends Object {

    const DELAY = 0;

    public $name = 'abstract';

    public $type = 'publisher';

    abstract public function enqueue($queue, $data);

    public function enqueueMany($queue, $data, $count){

        $pushed = [];

        for ($i = 0; $i < $count; $i++){
            $p = $this->enqueue($queue, $data);
            $this->log("Pushed $p");
            $this->debug("Pushed $p");
            $pushed[] = $p;
            usleep(self::DELAY);
        }

        return $pushed;
    }
}

