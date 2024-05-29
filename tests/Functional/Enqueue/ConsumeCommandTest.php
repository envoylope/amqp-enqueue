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

namespace Envoylope\EnqueueAmqp\Tests\Functional\Enqueue;

use Enqueue\Client\ProducerInterface;
use Enqueue\Symfony\Client\ConsumeCommand;
use Enqueue\Symfony\Client\SetupBrokerCommand;
use Envoylope\EnqueueAmqp\Tests\Functional\AbstractKernelTestCase;
use Envoylope\EnqueueAmqp\Tests\Functional\Util\Enqueue\FakeSignalExtension;
use Envoylope\EnqueueAmqp\Tests\Functional\Util\Enqueue\MyProcessor;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ConsumeCommandTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ConsumeCommandTest extends AbstractKernelTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        static::bootKernel(['environment' => 'consume_command']);

        (new CommandTester(new SetupBrokerCommand(self::getContainer(), 'default')))->execute([]);
    }

    public function testEventMessageCanBeProducedAndConsumedByEnqueue(): void
    {
        /** @var ProducerInterface $producer */
        $producer = self::getContainer()->get('enqueue.client.default.producer');
        $commandTester = new CommandTester(new ConsumeCommand(self::getContainer(), 'default'));
        /** @var MyProcessor $myProcessor */
        $myProcessor = self::getContainer()->get(MyProcessor::class);

        $producer->sendEvent('my_topic', 'my message');

        static::assertSame(0, $commandTester->execute([
            'client-queue-names' => ['default'],
            '--message-limit' => 2,
        ], [
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
        ]));
        static::assertSame('', $commandTester->getDisplay());
        static::assertEquals(['my message'], $myProcessor->getMessages());
    }

    public function testConsumptionCanBeInterrupted(): void
    {
        $commandTester = new CommandTester(new ConsumeCommand(self::getContainer(), 'default'));
        /** @var MyProcessor $myProcessor */
        $myProcessor = self::getContainer()->get(MyProcessor::class);
        /** @var FakeSignalExtension $fakeSignalExtension */
        $fakeSignalExtension = self::getContainer()->get(FakeSignalExtension::class);
        $fakeSignalExtension->interruptPostConsume();

        static::assertSame(0, $commandTester->execute([
            'client-queue-names' => ['default'],
            '--message-limit' => 2,
        ], [
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
        ]));
        static::assertSame('', $commandTester->getDisplay());
        static::assertEmpty($myProcessor->getMessages());
    }
}
