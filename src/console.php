#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use ConsoleMessage\ConsoleMessageWorkerRedisCommand;
use ConsoleMessage\ConsoleMessageWorkerSqsCommand;

$application = new Application();
$application->add(new ConsoleMessageWorkerRedisCommand());
$application->add(new ConsoleMessageWorkerSqsCommand());
$application->run();