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

namespace Envoylope\EnqueueAmqp\Tests\Integrated\Compat;

use AMQPChannel;
use AMQPConnection;
use Asmblah\PhpAmqpCompat\Bridge\AmqpBridge;
use Asmblah\PhpAmqpCompat\Bridge\Channel\AmqpChannelBridgeInterface;
use Asmblah\PhpAmqpCompat\Driver\Common\Channel\ChannelInterface;
use Asmblah\PhpAmqpCompat\Driver\Common\Logger\LoggerInterface;
use Asmblah\PhpAmqpCompat\Exception\StopConsumptionException;
use Enqueue\AmqpExt\AmqpConsumer;
use Enqueue\AmqpExt\AmqpContext;
use Enqueue\AmqpExt\AmqpSubscriptionConsumer;
use Envoylope\EnqueueAmqp\Tests\AbstractTestCase;
use Interop\Queue\Queue as InteropQueueInterface;
use Mockery\MockInterface;

/**
 * Class AmqpSubscriptionConsumerTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AmqpSubscriptionConsumerTest extends AbstractTestCase
{
    private MockInterface&AMQPConnection $amqpConnection;
    private MockInterface&ChannelInterface $channel;
    private AmqpSubscriptionConsumer $consumer;
    private MockInterface&LoggerInterface $logger;
    private MockInterface&AMQPChannel $amqpChannel;
    private MockInterface&AmqpChannelBridgeInterface $channelBridge;
    private MockInterface&AmqpContext $amqpContext;
    private MockInterface&InteropQueueInterface $amqplibQueue;
    private MockInterface&AmqpConsumer $amqpConsumer;

    public function setUp(): void
    {
        $this->amqpConnection = mock(AMQPConnection::class, [
            'getReadTimeout' => 10,
            'setReadTimeout' => true,
        ]);
        $this->channel = mock(ChannelInterface::class, [
            'basicConsume' => 'my-consumer-tag',
        ]);
        $this->logger = mock(LoggerInterface::class, [
            'debug' => null,
        ]);
        $this->amqpChannel = mock(AMQPChannel::class, [
            'getConnection' => $this->amqpConnection
        ]);
        $this->channelBridge = mock(AmqpChannelBridgeInterface::class, [
            'acquireChannel' => $this->channel,
            'getLogger' => $this->logger,
            'getReadTimeout' => 12,
            'getSubscribedConsumers' => [],
            'setConsumptionCallback' => null,
            'subscribeConsumer' => null,
        ]);
        AmqpBridge::bridgeChannel($this->amqpChannel, $this->channelBridge);
        $this->amqpContext = mock(AmqpContext::class, [
            'getExtChannel' => $this->amqpChannel,
        ]);
        $this->amqplibQueue = mock(InteropQueueInterface::class, [
            'getQueueName' => 'my.queue',
        ]);
        $this->consumer = new AmqpSubscriptionConsumer($this->amqpContext);
        $this->amqpConsumer = mock(AmqpConsumer::class, [
            'getConsumerTag' => 'my.consumer',
            'getFlags' => 0,
            'getQueue' => $this->amqplibQueue,
            'setConsumerTag' => null,
        ]);
    }

    public function testConsumerCanStart(): void
    {
        $this->channel->expects('wait')
            ->once()
            ->andThrow(new StopConsumptionException());

        $this->consumer->subscribe($this->amqpConsumer, function () {});
        $this->consumer->consume();
    }
}
