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

use Enqueue\Client\TopicSubscriberInterface;
use Enqueue\Consumption\Result;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;

/**
 * Class MyProcessor.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class MyProcessor implements Processor, TopicSubscriberInterface
{
    /**
     * @var string[]
     */
    private array $messages = [];

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    public function process(Message $message, Context $context): string
    {
        $this->messages[] = $message->getBody();

        return Result::ACK;
    }

    /**
     * @inheritDoc
     *
     * @return string[]
     */
    public static function getSubscribedTopics(): array
    {
        return ['my_topic'];
    }
}
