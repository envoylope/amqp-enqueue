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

namespace Envoylope\EnqueueAmqp\Tests\Functional\Util\Kernel;

use Enqueue\Bundle\EnqueueBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class TestKernel.
 *
 * Kernel that is solely used for functional testing of the plugin.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestKernel extends Kernel
{
    /**
     * @inheritDoc
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new MonologBundle(),
            new EnqueueBundle(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getProjectDir(): string
    {
        return __DIR__;
    }

    /**
     * @inheritDoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getProjectDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * @inheritDoc
     */
    public function getCacheDir(): string
    {
        return realpath(__DIR__ . '/../../../../') . '/var/' . $this->environment . '/cache';
    }

    /**
     * @inheritDoc
     */
    public function getLogDir(): string
    {
        return realpath(__DIR__ . '/../../../../') . '/var/' . $this->environment . '/logs';
    }
}
