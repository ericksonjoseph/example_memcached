<?php

include_once '/global/app/bootstrap.php';

class Object {

    private static $log_dir;

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
        return "$this->name.$this->type: $message" . PHP_EOL;
    }

    private function setLogDir()
    {
    }

    private function isValid()
    {
        return is_string($this->name) && is_string($this->type);
    }

}
