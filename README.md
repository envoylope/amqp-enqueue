# Envoylope Enqueue AMQP Nytris Plugin

[![Build Status](https://github.com/envoylope/amqp-enqueue/workflows/CI/badge.svg)](https://github.com/envoylope/amqp-enqueue/actions?query=workflow%3ACI)

Integrates [Enqueue][Enqueue] into an application using [PHP AMQP-Compat][PHP AMQP-Compat] via [Enqueue amqp-ext][Enqueue amqp-ext].

## Usage
Install this package with Composer as a Nytris package:

```shell
$ composer install envoylope/amqp-enqueue
```

### Configuring Nytris platform

Configure [Nytris platform][Nytris platform] to use this package:

`nytris.config.php`:

```php
<?php

declare(strict_types=1);

use Envoylope\EnqueueAmqp\EnqueueAmqpPackage;
use Nytris\Boot\BootConfig;
use Nytris\Boot\PlatformConfig;

$bootConfig = new BootConfig(new PlatformConfig(__DIR__ . '/var/cache/nytris'));

// ...

$bootConfig->installPackage(new EnqueueAmqpPackage());

// ...

return $bootConfig;
```

You'll probably also want to install [Nytris Shift Symfony][Nytris Shift Symfony] for transpiled code caching.

## See also
- [PHP AMQP-Compat][PHP AMQP-Compat], which is used by this library.

[Enqueue]: https://github.com/php-enqueue/enqueue-dev
[Enqueue amqp-ext]: https://github.com/php-enqueue/amqp-ext
[Nytris Bundle]: https://github.com/nytris/bundle
[Nytris platform]: https://github.com/nytris/nytris
[Nytris Shift Symfony]: https://github.com/nytris/shift-symfony
[PHP AMQP-Compat]: https://github.com/asmblah/php-amqp-compat
