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

namespace Envoylope\EnqueueAmqp;

use Nytris\Core\Package\PackageInterface;

/**
 * Interface EnqueueAmqpPackageInterface.
 *
 * Configures the installation of Envoylope AMQP.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface EnqueueAmqpPackageInterface extends PackageInterface
{
}
