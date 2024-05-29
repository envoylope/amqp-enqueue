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
use Asmblah\PhpAmqpCompat\Bridge\Channel\EnvelopeTransformerInterface;
use Asmblah\PhpAmqpCompat\Driver\Common\Exception\ExceptionHandlerInterface;
use Asmblah\PhpAmqpCompat\Exception\StopConsumptionException;
use Asmblah\PhpAmqpCompat\Logger\LoggerInterface;
use Enqueue\AmqpExt\AmqpConsumer;
use Enqueue\AmqpExt\AmqpContext;
use Enqueue\AmqpExt\AmqpSubscriptionConsumer;
use Envoylope\EnqueueAmqp\Tests\AbstractTestCase;
use Interop\Queue\Queue as InteropQueueInterface;
use Mockery\MockInterface;
use PhpAmqpLib\Channel\AMQPChannel as AmqplibChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class AmqpSubscriptionConsumerTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AmqpSubscriptionConsumerTest extends AbstractTestCase
{
    private MockInterface&AMQPStreamConnection $amqplibConnection;
    private MockInterface&AMQPConnection $amqpConnection;
    private MockInterface&AmqplibChannel $amqplibChannel;
    private AmqpSubscriptionConsumer $consumer;
    private MockInterface&EnvelopeTransformerInterface $envelopeTransformer;
    private MockInterface&ExceptionHandlerInterface $exceptionHandler;
    private MockInterface&LoggerInterface $logger;
    private MockInterface&AMQPChannel $amqpChannel;
    private MockInterface&AmqpChannelBridgeInterface $channelBridge;
    private MockInterface&AmqpContext $amqpContext;
    private MockInterface&InteropQueueInterface $amqplibQueue;
    private MockInterface&AmqpConsumer $amqpConsumer;

    public function setUp(): void
    {
        $this->amqplibConnection = mock(AMQPStreamConnection::class, [
            'isConnected' => true,
        ]);
        $this->amqpConnection = mock(AMQPConnection::class, [
            'getReadTimeout' => 10,
            'setReadTimeout' => true,
        ]);
        $this->amqplibChannel = mock(AmqplibChannel::class, [
            'basic_consume' => 'my.consumer.tag', // ???
            'getConnection' => $this->amqplibConnection,
            'is_open' => true,
        ]);
        $this->envelopeTransformer = mock(EnvelopeTransformerInterface::class);
        $this->exceptionHandler = mock(ExceptionHandlerInterface::class);
        $this->logger = mock(LoggerInterface::class, [
            'debug' => null,
        ]);
        $this->amqpChannel = mock(AMQPChannel::class, [
            'getConnection' => $this->amqpConnection
        ]);
        $this->channelBridge = mock(AmqpChannelBridgeInterface::class, [
            'getAmqplibChannel' => $this->amqplibChannel,
            'getEnvelopeTransformer' => $this->envelopeTransformer,
            'getExceptionHandler' => $this->exceptionHandler,
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
        $this->amqplibChannel->expects('wait')
            ->once()
            ->andThrow(new StopConsumptionException());

        $this->consumer->subscribe($this->amqpConsumer, function () {});
        $this->consumer->consume();
    }
}
