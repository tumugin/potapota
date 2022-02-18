<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\ApplicationSettings\Repository;

use Symfony\Component\Dotenv\Dotenv;
use Tumugin\Potapota\Domain\Application\ApplicationSettings;
use Tumugin\Potapota\Domain\Application\ApplicationSettingsRepository;
use Tumugin\Potapota\Domain\Application\ClickUpAPIToken;
use Tumugin\Potapota\Domain\Application\ClickUpListId;
use Tumugin\Potapota\Domain\Application\ClickUpSetting;
use Tumugin\Potapota\Domain\Application\ClickUpSettingMap;
use Tumugin\Potapota\Domain\Application\DiscordToken;
use Tumugin\Potapota\Domain\Application\DiscordTriggerEmoji;

class ApplicationSettingsRepositoryImpl implements ApplicationSettingsRepository
{
    public function getApplicationSettings(): ApplicationSettings
    {
        $this->loadEnv();
        return new ApplicationSettings(
            DiscordToken::byString(getenv('DISCORD_TOKEN')),
            DiscordTriggerEmoji::byString(getenv('DISCORD_TRIGGER_EMOJI')),
            $this->createClickUpSettingMapByEnv(getenv())
        );
    }

    public function createClickUpSettingMapByEnv(array $envValues): ClickUpSettingMap
    {
        $guildIds = $this->getGuildIdsFromSetting($envValues);
        $baseMapValues = [];

        foreach ($guildIds as $guildId) {
            $clickUpSetting = new ClickUpSetting(
                ClickUpAPIToken::byString($envValues["GUILD_ID_{$guildId}_CLICKUP_API_TOKEN"]),
                ClickUpListId::byString($envValues["GUILD_ID_{$guildId}_CLICKUP_LIST_ID"])
            );
            $baseMapValues[$guildId] = $clickUpSetting;
        }

        return new ClickUpSettingMap($baseMapValues);
    }

    /**
     * @return string[]
     */
    private function getGuildIdsFromSetting(array $envValues): array
    {
        $discordGuildIds = [];
        foreach ($envValues as $key => $_) {
            $matches = [];
            preg_match('/^GUILD_ID_([0-9]+)_/u', $key, $matches);
            if (isset($matches[1])) {
                $discordGuildIds[] = $matches[1];
            }
        }

        return $discordGuildIds;
    }

    private function loadEnv(): void
    {
        $dotenv = new Dotenv();
        $dotenv->usePutenv(true);
        $dotenv->loadEnv('.env', 'ENV');
    }
}
