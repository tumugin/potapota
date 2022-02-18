<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

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
