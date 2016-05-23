<?php

require APP_ROOT . 'src/Object.php';

abstract class Publisher extends Object {

    const DELAY = 0;

    public $type = 'publisher';

    public function enqueue(&$queue, array &$data)
    {
        $id = $this->createUniqueID();
        $this->log($id, "$this->name.$this->type.assigned_ids.log");
        $data['_id'] = $id;
        $data = json_encode($data);
        return true;
    }

    public function enqueueMany($queue, $data, $count){

        $pushed = [];

        for ($i = 0; $i < $count; $i++){
            $p = $this->enqueue($queue, $data);
            //$this->log("Pushed $p");
            $this->debug("Pushed $p");
            $pushed[] = $p;
            usleep(self::DELAY);
        }

        return $pushed;
    }
}

