<?php

declare(strict_types=1);

namespace Tumugin\Potapota\DI;

use DI\ContainerBuilder;
use Discord\Discord;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Tumugin\Potapota\Console\Main;
use Tumugin\Potapota\Domain\Application\ApplicationSettings;
use Tumugin\Potapota\Domain\Application\ApplicationSettingsRepository;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskRepository;
use Tumugin\Potapota\Domain\Discord\DiscordEventRepository;
use Tumugin\Potapota\Domain\Discord\DiscordMessageRepository;
use Tumugin\Potapota\Domain\Discord\MessageEventRepository;
use Tumugin\Potapota\Infra\ApplicationSettings\Repository\ApplicationSettingsRepositoryImpl;
use Tumugin\Potapota\Infra\ClickUp\Repository\ClickUpTaskRepositoryImpl;
use Tumugin\Potapota\Infra\Discord\Repository\DiscordEventRepositoryImpl;
use Tumugin\Potapota\Infra\Discord\Repository\DiscordMessageRepositoryImpl;
use Tumugin\Potapota\Infra\Discord\Repository\MessageEventRepositoryImpl;
use Tumugin\Potapota\Logger\LoggerSettings;

use function DI\autowire;
use function DI\factory;
use function DI\get;

class Container
{
    public static function createContainer(): \DI\Container
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->addDefinitions(self::getDefinitions());
        return $containerBuilder->build();
    }

    /**
     * @return array<string, mixed>
     */
    private static function getDefinitions(): array
    {
        return [
            // Library
            Application::class => factory(fn() => new Application()),
            LoggerInterface::class => factory(fn() => new Logger('potapota')),
            Logger::class => get(LoggerInterface::class),
            Main::class => autowire(Main::class),
            LoggerSettings::class => autowire(LoggerSettings::class),
            Discord::class => fn(
                ApplicationSettings $applicationSettings,
                LoggerInterface $logger
            ) => new Discord([
                'token' => $applicationSettings->discordToken->toString(),
                'logger' => $logger,
            ]),
            // Domain
            ApplicationSettings::class => fn(
                ApplicationSettingsRepository $applicationSettingsRepository
            ) => $applicationSettingsRepository->getApplicationSettings(),
            MessageEventRepository::class => autowire(MessageEventRepositoryImpl::class),
            ClickUpTaskRepository::class => autowire(ClickUpTaskRepositoryImpl::class),
            DiscordMessageRepository::class => autowire(DiscordMessageRepositoryImpl::class),
            ApplicationSettingsRepository::class => autowire(ApplicationSettingsRepositoryImpl::class),
            DiscordEventRepository::class => autowire(DiscordEventRepositoryImpl::class),
        ];
    }
}
