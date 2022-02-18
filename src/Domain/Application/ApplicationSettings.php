<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

class ApplicationSettings
{
    private DiscordToken $discordToken;
    private DiscordTriggerEmoji $discordTriggerEmoji;
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

    public function getDiscordToken(): DiscordToken
    {
        return $this->discordToken;
    }

    public function getDiscordTriggerEmoji(): DiscordTriggerEmoji
    {
        return $this->discordTriggerEmoji;
    }
}
