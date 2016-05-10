#!/bin/bash
<?php

/* Dependencies */
require '/global/app/bootstrap.php';
require APP_ROOT . 'src/RedisWorker.php';

$Worker = new RedisWorker();

$Worker->subscribe('transactions');
