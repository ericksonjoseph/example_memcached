#!/bin/bash
<?php

/* Dependencies */
require '/global/app/bootstrap.php';
require APP_ROOT . 'src/RedisWorkerCluster.php';

$Worker = new RedisWorkerCluster();

$Worker->subscribe('transactions');
