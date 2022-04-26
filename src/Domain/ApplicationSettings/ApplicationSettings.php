<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ApplicationSettings;

use Tumugin\Potapota\Domain\ClickUp\ClickUpSettingMap;
use Tumugin\Potapota\Domain\Discord\DiscordToken;
use Tumugin\Potapota\Domain\Discord\DiscordTriggerEmoji;
use Tumugin\Potapota\Domain\Sentry\SentryDsn;

class ApplicationSettings
{
    public readonly DiscordToken $discordToken;
    public readonly DiscordTriggerEmoji $discordTriggerEmoji;
    public readonly ClickUpSettingMap $clickUpSettingMap;
    public readonly ?SentryDsn $sentryDsn;

    public function __construct(
        DiscordToken $discordToken,
        DiscordTriggerEmoji $discordTriggerEmoji,
        ClickUpSettingMap $clickUpSettingMap,
        ?SentryDsn $sentryDsn
    ) {
        $this->discordToken = $discordToken;
        $this->discordTriggerEmoji = $discordTriggerEmoji;
        $this->clickUpSettingMap = $clickUpSettingMap;
        $this->sentryDsn = $sentryDsn;
    }
}
