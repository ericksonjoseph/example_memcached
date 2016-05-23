<?php

require APP_ROOT . 'src/RedisWorker.php';

class RedisWorkerCluster extends RedisWorker {

    public function __construct()
    {
        parent::__construct();
        $this->setupRedisDriver();
    }

    public function flushall()
    {
        $this->iterateMasters(function($master){
            $this->driver->flushall($master);
        });
    }

    private function iterateMasters($callback)
    {
        foreach ($this->driver->_masters() as $k => $master){
            $callback($master);
        }
    }

    /**
     * @inheritdoc
     */
    private function setupRedisDriver()
    {
        $cluster = array('127.0.0.1:7000','127.0.0.1:7001','127.0.0.1:7002','127.0.0.1:7003','127.0.0.1:7004','127.0.0.1:7005');
        $this->driver = new RedisCluster(NULL, $cluster);
        /* Use this to issue READONLY commands to the cluster */
        $this->driver->setOption(
            RedisCluster::OPT_SLAVE_FAILOVER, RedisCluster::FAILOVER_DISTRIBUTE
        );
    }
}

