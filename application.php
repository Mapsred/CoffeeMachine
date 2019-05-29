#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use App\Command\CoffeeMachineCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new CoffeeMachineCommand());

$application->run();
