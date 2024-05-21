<?php

/*
 * Envoylope Enqueue AMQP Nytris Plugin
 * Copyright (c) Dan Phillimore (asmblah)
 * https://github.com/envoylope/amqp-enqueue/
 *
 * Released under the MIT license.
 * https://github.com/envoylope/amqp-enqueue/raw/main/MIT-LICENSE.txt
 */

declare(strict_types=1);

use Asmblah\PhpAmqpCompat\AmqpCompatPackage;
use Envoylope\EnqueueAmqp\EnqueueAmqpPackage;
use Nytris\Boot\BootConfig;
use Nytris\Boot\PlatformConfig;
use Nytris\Nytris;

require_once __DIR__ . '/../vendor/autoload.php';

Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
Mockery::globalHelpers();

$bootConfig = new BootConfig(new PlatformConfig(dirname(__DIR__) . '/var/nytris/'));
$bootConfig->installPackage(new AmqpCompatPackage());
$bootConfig->installPackage(new EnqueueAmqpPackage());
Nytris::boot($bootConfig);
