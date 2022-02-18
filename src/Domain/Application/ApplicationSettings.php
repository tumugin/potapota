<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

class ApplicationSettings
{
    public readonly DiscordToken $discordToken;
    public readonly DiscordTriggerEmoji $discordTriggerEmoji;
    readonly public ClickUpSettingMap $clickUpSettingMap;

    public function __construct(
        DiscordToken $discordToken,
        DiscordTriggerEmoji $discordTriggerEmoji,
        ClickUpSettingMap $clickUpSettingMap
    ) {
        $this->discordToken = $discordToken;
        $this->discordTriggerEmoji = $discordTriggerEmoji;
        $this->clickUpSettingMap = $clickUpSettingMap;
    }
}
