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

namespace Envoylope\EnqueueAmqp\Tests\Functional\Util\Enqueue;

use Enqueue\Consumption\Context\PostConsume;
use Enqueue\Consumption\PostConsumeExtensionInterface;

/**
 * Class FakeSignalExtension.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class FakeSignalExtension implements PostConsumeExtensionInterface
{
    private bool $shouldInterrupt = false;

    public function interruptPostConsume(): void
    {
        $this->shouldInterrupt = true;
    }

    /**
     * @inheritDoc
     */
    public function onPostConsume(PostConsume $context): void
    {
        if ($this->shouldInterrupt) {
            $context->interruptExecution();
        }
    }
}
