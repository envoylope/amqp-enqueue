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

namespace Envoylope\EnqueueAmqp\Tests\Functional;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class AbstractKernelTestCase.
 *
 * Base class for all kernel test cases.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
abstract class AbstractKernelTestCase extends KernelTestCase
{
    use MockeryPHPUnitIntegration;
}
