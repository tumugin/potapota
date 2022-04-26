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
use Tumugin\Potapota\Domain\Sentry\SentryDsn;
use Tumugin\Potapota\Domain\TaskServiceSelection\TaskServiceSelection;
use Tumugin\Potapota\Domain\TaskServiceSelection\TaskServiceSelectionSettingMap;
use Tumugin\Potapota\Domain\Trello\TrelloAPIKey;
use Tumugin\Potapota\Domain\Trello\TrelloAPIToken;
use Tumugin\Potapota\Domain\Trello\TrelloListId;
use Tumugin\Potapota\Domain\Trello\TrelloSetting;
use Tumugin\Potapota\Domain\Trello\TrelloSettingMap;
use Tumugin\Stannum\SnList\SnStringList;
use Tumugin\Stannum\SnString;

class ApplicationSettingsRepositoryImpl implements ApplicationSettingsRepository
{
    /**
     * @throws AssertionFailedException
     * @throws RequiredEnvNotFoundException
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
            getenv('SENTRY_DSN') ? SentryDsn::byString(getenv('SENTRY_DSN')) : null,
            $this->createTaskServiceSelectionSettingMapByEnv(getenv()),
            $this->createTrelloSettingMapByEnv(getenv())
        );
    }

    /**
     * @param array<string, string> $envValues
     * @return ClickUpSettingMap
     * @throws AssertionFailedException
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
     * @return TrelloSettingMap
     * @throws AssertionFailedException
     */
    public function createTrelloSettingMapByEnv(array $envValues): TrelloSettingMap
    {
        $guildIds = $this->getGuildIdsFromSetting($envValues);
        $baseMapValues = [];

        foreach ($guildIds as $guildId) {
            $trelloSetting = new TrelloSetting(
                TrelloAPIKey::byString($envValues["GUILD_ID_{$guildId}_TRELLO_API_KEY"]),
                TrelloAPIToken::byString($envValues["GUILD_ID_{$guildId}_TRELLO_API_TOKEN"]),
                TrelloListId::byString($envValues["GUILD_ID_{$guildId}_TRELLO_LIST_ID"]),
            );
            $baseMapValues[$guildId->toString()] = $trelloSetting;
        }

        return new TrelloSettingMap($baseMapValues);
    }

    /**
     * @param array<string, string> $envValues
     * @return TaskServiceSelectionSettingMap
     */
    public function createTaskServiceSelectionSettingMapByEnv(array $envValues): TaskServiceSelectionSettingMap
    {
        $guildIds = $this->getGuildIdsFromSetting($envValues);
        $baseMapValues = [];

        foreach ($guildIds as $guildId) {
            $taskServiceSelection = TaskServiceSelection::createByString(
                $envValues["GUILD_ID_{$guildId}_TASK_SERVICE"]
            );
            $baseMapValues[$guildId->toString()] = $taskServiceSelection;
        }

        return new TaskServiceSelectionSettingMap($baseMapValues);
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
