#!/bin/bash
<?php

require '/global/app/bootstrap.php';
require APP_ROOT . 'src/MemqWorker.php';

$Worker = new MemqWorker();

$Worker->subscribe('transactions');
