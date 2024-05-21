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

use Asmblah\PhpAmqpCompat\AmqpCompatInterface;
use Asmblah\PhpCodeShift\CodeShift;
use Asmblah\PhpCodeShift\CodeShiftInterface;
use Asmblah\PhpCodeShift\Shifter\Filter\FileFilter;
use Asmblah\PhpCodeShift\Shifter\Shift\Shift\FunctionHook\FunctionHookShiftSpec;
use InvalidArgumentException;
use Nytris\Core\Package\PackageContextInterface;
use Nytris\Core\Package\PackageFacadeInterface;
use Nytris\Core\Package\PackageInterface;

/**
 * Class EnqueueAmqp.
 *
 * Defines the public facade API for the library.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class EnqueueAmqp implements PackageFacadeInterface
{
    private static ?CodeShiftInterface $codeShift = null;
    private static bool $installed = false;

    /**
     * @inheritDoc
     */
    public static function getName(): string
    {
        return 'amqp-enqueue';
    }

    /**
     * @inheritDoc
     */
    public static function getVendor(): string
    {
        return 'envoylope';
    }

    /**
     * @inheritDoc
     */
    public static function install(PackageContextInterface $packageContext, PackageInterface $package): void
    {
        if (!$package instanceof EnqueueAmqpPackageInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Package config must be a %s but it was a %s',
                    EnqueueAmqpPackageInterface::class,
                    $package::class
                )
            );
        }

        self::$codeShift = new CodeShift();
        self::$codeShift->shift(
            new FunctionHookShiftSpec(
                'phpversion',
                function (callable $originalPhpversion) {
                    return static function (?string $extensionName = null) use ($originalPhpversion) {
                        if ($extensionName === 'amqp') {
                            return AmqpCompatInterface::AMQP_EXT_EMULATION_VERSION;
                        }

                        return $originalPhpversion($extensionName);
                    };
                }
            ),
            new FileFilter('**/vendor/enqueue/amqp-ext/AmqpSubscriptionConsumer.php')
        );

        self::$installed = true;
    }

    /**
     * @inheritDoc
     */
    public static function isInstalled(): bool
    {
        return self::$installed;
    }

    /**
     * @inheritDoc
     */
    public static function uninstall(): void
    {
        if (!self::$installed) {
            return;
        }

        self::$codeShift->uninstall();
        self::$codeShift = null;
        self::$installed = false;
    }
}
