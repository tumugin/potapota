<?php

namespace Tumugin\Potapota\Console;

use Tumugin\Potapota\DI\Container;
use Tumugin\Potapota\Logger\LoggerSettings;

class Entrypoint
{
    public static function main()
    {
        $container = Container::createContainer();

        $loggerSettings = $container->get(LoggerSettings::class);
        $loggerSettings->setup();

        $main = $container->get(Main::class);
        $main->execute();
    }
}
