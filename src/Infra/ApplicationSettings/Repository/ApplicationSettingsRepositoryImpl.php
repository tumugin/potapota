<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\ApplicationSettings\Repository;

use Assert\AssertionFailedException;
use Symfony\Component\Dotenv\Dotenv;
use Tumugin\Potapota\Domain\ApplicationSettings\ApplicationSettings;
use Tumugin\Potapota\Domain\ApplicationSettings\ApplicationSettingsRepository;
use Tumugin\Potapota\Domain\ClickUp\ClickUpAPIToken;
use Tumugin\Potapota\Domain\ClickUp\ClickUpListId;
use Tumugin\Potapota\Domain\ClickUp\ClickUpSetting;
use Tumugin\Potapota\Domain\ClickUp\ClickUpSettingMap;
use Tumugin\Potapota\Domain\Discord\DiscordToken;
use Tumugin\Potapota\Domain\Discord\DiscordTriggerEmoji;
use Tumugin\Potapota\Domain\Exceptions\RequiredEnvNotFoundException;
use Tumugin\Potapota\Domain\Exceptions\SettingException;
use Tumugin\Potapota\Domain\Sentry\SentryDsn;
use Tumugin\Stannum\SnList\SnStringList;
use Tumugin\Stannum\SnString;

class ApplicationSettingsRepositoryImpl implements ApplicationSettingsRepository
{
    /**
     * @throws AssertionFailedException
     * @throws RequiredEnvNotFoundException
     * @throws SettingException
     */
    public function getApplicationSettings(): ApplicationSettings
    {
        $this->loadEnv();
        return new ApplicationSettings(
            DiscordToken::byString(
                getenv('DISCORD_TOKEN') ?: throw new RequiredEnvNotFoundException(
                    'Required env DISCORD_TOKEN not found.'
                )
            ),
            DiscordTriggerEmoji::byString(
                getenv('DISCORD_TRIGGER_EMOJI') ?: throw new RequiredEnvNotFoundException(
                    'Required env DISCORD_TRIGGER_EMOJI not found.'
                )
            ),
            $this->createClickUpSettingMapByEnv(getenv()),
            getenv('SENTRY_DSN') ? SentryDsn::byString(getenv('SENTRY_DSN')) : null
        );
    }

    /**
     * @param array<string, string> $envValues
     * @return ClickUpSettingMap
     * @throws AssertionFailedException
     * @throws SettingException
     */
    public function createClickUpSettingMapByEnv(array $envValues): ClickUpSettingMap
    {
        $guildIds = $this->getGuildIdsFromSetting($envValues);
        $baseMapValues = [];

        foreach ($guildIds as $guildId) {
            $clickUpSetting = new ClickUpSetting(
                ClickUpAPIToken::byString($envValues["GUILD_ID_{$guildId}_CLICKUP_API_TOKEN"]),
                ClickUpListId::byString($envValues["GUILD_ID_{$guildId}_CLICKUP_LIST_ID"])
            );
            $baseMapValues[$guildId->toString()] = $clickUpSetting;
        }

        return new ClickUpSettingMap($baseMapValues);
    }

    /**
     * @param array<string, string> $envValues
     */
    private function getGuildIdsFromSetting(array $envValues): SnStringList
    {
        return SnStringList::byStringArray(array_keys($envValues))
            ->map(
                fn(SnString $str) => $str
                    ->pregMatchAll(SnString::byString('/^GUILD_ID_([0-9]+)_/u'))
                    ?->getMatchGroups()
                    ->first()
            )
            ->filter(fn(?Snstring $str) => !is_null($str))
            ->toSnStringList();
    }

    private function loadEnv(): void
    {
        $dotenv = new Dotenv();
        $dotenv->usePutenv(true);
        $dotenv->loadEnv('.env', 'ENV');
    }
}
