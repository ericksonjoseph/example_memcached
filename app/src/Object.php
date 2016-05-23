<?php

include_once '/global/app/bootstrap.php';

class Object {

    private static $log_dir;

    /**
     * Used to uniquely identity an instance of this class
     * 
     * @var string
     * @access protected
     */
    protected $id;

    public function __construct()
    {
        $this->id = "ID" . $this->createUniqueID();
        $this->log($this->id, $this->name . '.' . $this->type .'.id.log');
    }

    protected function createUniqueID()
    {
        $dte = new \DateTime();
        $ts = $dte->getTimestamp();
        return uniqid($ts, true);
    }

    protected function debug($message)
    {
        echo $this->formatMessage($message);
        return true;
    }

    protected function log($message, $file = null)
    {
        if (!$this->isValid() || !is_string($message)){
            throw \RuntimeException('Please provide a proper name & type & message');
        }

        $message = $this->formatMessage($message);

        // Set filename
        $filename = $this->name . '.' . $this->type .'.log';
        if ($file) {
            $filename = $file;
        }

        if (!is_dir(LOG_DIR)){
            if(!mkdir(LOG_DIR)){
                throw new \RuntimeException('Unable to create the logging directory');
            }
        }

        return file_put_contents(LOG_DIR . $filename, $message, FILE_APPEND);
    }

    private function formatMessage($message)
    {
        if (!$this->isValid() || !is_string($message)){
            throw \RuntimeException('Please provide a proper name & type & message');
        }
        return "$this->name.$this->type: $this->id $message" . PHP_EOL;
    }

    private function setLogDir()
    {
    }

    private function isValid()
    {
        return is_string($this->name) && is_string($this->type);
    }

}
