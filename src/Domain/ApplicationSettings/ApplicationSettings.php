<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ApplicationSettings;

use Tumugin\Potapota\Domain\ClickUp\ClickUpSettingMap;
use Tumugin\Potapota\Domain\Discord\DiscordGuildId;
use Tumugin\Potapota\Domain\Discord\DiscordToken;
use Tumugin\Potapota\Domain\Discord\DiscordTriggerEmoji;
use Tumugin\Potapota\Domain\Exceptions\PotapotaUnexpectedConditionException;
use Tumugin\Potapota\Domain\Sentry\SentryDsn;
use Tumugin\Potapota\Domain\TaskServiceSelection\TaskServiceSelection;
use Tumugin\Potapota\Domain\TaskServiceSelection\TaskServiceSelectionSettingMap;
use Tumugin\Potapota\Domain\Trello\TrelloSettingMap;

class ApplicationSettings
{
    public readonly DiscordToken $discordToken;
    public readonly DiscordTriggerEmoji $discordTriggerEmoji;
    public readonly ClickUpSettingMap $clickUpSettingMap;
    public readonly TrelloSettingMap $trelloSettingMap;
    public readonly ?SentryDsn $sentryDsn;
    public readonly TaskServiceSelectionSettingMap $taskServiceSelectionSettingMap;

    public function __construct(
        DiscordToken $discordToken,
        DiscordTriggerEmoji $discordTriggerEmoji,
        ClickUpSettingMap $clickUpSettingMap,
        ?SentryDsn $sentryDsn,
        TaskServiceSelectionSettingMap $taskServiceSelectionSettingMap,
        TrelloSettingMap $trelloSettingMap
    ) {
        $this->discordToken = $discordToken;
        $this->discordTriggerEmoji = $discordTriggerEmoji;
        $this->clickUpSettingMap = $clickUpSettingMap;
        $this->trelloSettingMap = $trelloSettingMap;
        $this->sentryDsn = $sentryDsn;
        $this->taskServiceSelectionSettingMap = $taskServiceSelectionSettingMap;
    }

    public function checkDiscordGuildHasSetting(DiscordGuildId $discordGuildId): bool
    {
        if (!$this->taskServiceSelectionSettingMap->settingsOfDiscordGuildIdExists($discordGuildId)) {
            return false;
        }

        $taskServiceSelection = $this->taskServiceSelectionSettingMap->getSettingByDiscordGuildId($discordGuildId);

        return match ($taskServiceSelection) {
            TaskServiceSelection::CLICKUP => $this->clickUpSettingMap->settingsOfDiscordGuildIdExists($discordGuildId),
            TaskServiceSelection::TRELLO => $this->trelloSettingMap->settingsOfDiscordGuildIdExists($discordGuildId),
            default => throw new PotapotaUnexpectedConditionException(),
        };
    }
}
