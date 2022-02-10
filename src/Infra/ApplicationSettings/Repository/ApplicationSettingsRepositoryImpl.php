<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\ApplicationSettings\Repository;

use Symfony\Component\Dotenv\Dotenv;
use Tumugin\Potapota\Domain\Application\ApplicationSettings;
use Tumugin\Potapota\Domain\Application\ApplicationSettingsRepository;
use Tumugin\Potapota\Domain\Application\ClickUpAPIToken;
use Tumugin\Potapota\Domain\Application\ClickUpListId;
use Tumugin\Potapota\Domain\Application\ClickUpProjectId;
use Tumugin\Potapota\Domain\Application\ClickUpSpaceId;
use Tumugin\Potapota\Domain\Application\ClickUpTeamId;
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
        $this->loadEnv();
        return new ApplicationSettings(
            DiscordToken::byString(getenv('DISCORD_TOKEN')),
            DiscordTriggerEmoji::byString(getenv('DISCORD_TRIGGER_EMOJI')),
            ClickUpAPIToken::byString(getenv('CLICKUP_API_TOKEN')),
            ClickUpTeamId::byString(getenv('CLICKUP_TEAM_ID')),
            ClickUpSpaceId::byString(getenv('CLICKUP_SPACE_ID')),
            ClickUpProjectId::byString(getenv('CLICKUP_PROJECT_ID')),
            ClickUpListId::byString(getenv('CLICKUP_LIST_ID'))
        );
    }

    private function loadEnv(): void
    {
        $this->dotenv->load(__DIR__ . '/.env');
    }
}
