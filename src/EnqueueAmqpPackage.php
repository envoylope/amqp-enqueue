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

/**
 * Class EnqueueAmqpPackage.
 *
 * Configures the installation of Envoylope AMQP.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class EnqueueAmqpPackage implements EnqueueAmqpPackageInterface
{
    /**
     * @inheritDoc
     */
    public function getPackageFacadeFqcn(): string
    {
        return EnqueueAmqp::class;
    }
}
