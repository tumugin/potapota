<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Console;

use Tumugin\Potapota\DI\Container;
use Tumugin\Potapota\Logger\LoggerSettings;

class Entrypoint
{
    public static function main(): void
    {
        $container = Container::createContainer();

        /**
         * @var LoggerSettings $loggerSettings
         */
        $loggerSettings = $container->get(LoggerSettings::class);
        $loggerSettings->setup();

        /**
         * @var Main $main
         */
        $main = $container->get(Main::class);
        $main->execute();
    }
}
