#!/bin/bash
<?php

/* Dependencies */
require '/global/app/bootstrap.php';
require APP_ROOT . 'src/RedisPublisher.php';

$Publisher = new RedisPublisher();

$data_for_queue = '{class:"BatchProcessor", transactions: [1,2,3]';

$Publisher->enqueueMany('transactions', $data_for_queue, 5);
