#!/bin/bash
<?php

require '/global/app/bootstrap.php';
require APP_ROOT . 'src/MemqPublisher.php';

$Publisher = new MemqPublisher();

$data_for_queue = '{class:"BatchProcessor", transactions: [1,2,3]';

$Publisher->enqueueMany('transactions', $data_for_queue, 5);
