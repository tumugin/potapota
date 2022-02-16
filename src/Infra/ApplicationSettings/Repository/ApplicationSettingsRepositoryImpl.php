<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\ApplicationSettings\Repository;

use Symfony\Component\Dotenv\Dotenv;
use Tumugin\Potapota\Domain\Application\ApplicationEnvironment;
use Tumugin\Potapota\Domain\Application\ApplicationSettings;
use Tumugin\Potapota\Domain\Application\ApplicationSettingsRepository;
use Tumugin\Potapota\Domain\Application\ClickUpAPIToken;
use Tumugin\Potapota\Domain\Application\ClickUpListId;
use Tumugin\Potapota\Domain\Application\DiscordToken;
use Tumugin\Potapota\Domain\Application\DiscordTriggerEmoji;

class ApplicationSettingsRepositoryImpl implements ApplicationSettingsRepository
{
    private Dotenv $dotenv;

    public function __construct(Dotenv $dotenv)
    {
        $this->dotenv = $dotenv;
    }

    public function getApplicationSettings(): ApplicationSettings
    {
        $this->loadEnv(
            ApplicationEnvironment::fromString(getenv('ENV') ?? '')
        );
        return new ApplicationSettings(
            DiscordToken::byString(getenv('DISCORD_TOKEN')),
            DiscordTriggerEmoji::byString(getenv('DISCORD_TRIGGER_EMOJI')),
            ClickUpAPIToken::byString(getenv('CLICKUP_API_TOKEN')),
            ClickUpListId::byString(getenv('CLICKUP_LIST_ID'))
        );
    }

    private function loadEnv(ApplicationEnvironment $applicationEnvironment): void
    {
        $envInString = $applicationEnvironment->toString();
        $this->dotenv->load(__DIR__ . "/{$envInString}.env");
    }
}
