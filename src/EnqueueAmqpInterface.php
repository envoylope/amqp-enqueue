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

use Nytris\Core\Package\PackageFacadeInterface;

/**
 * Interface EnqueueAmqpInterface.
 *
 * Defines the public facade API for the library.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface EnqueueAmqpInterface extends PackageFacadeInterface
{
}
