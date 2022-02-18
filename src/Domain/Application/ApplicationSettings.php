<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

class ApplicationSettings
{
    private DiscordToken $discordToken;
    private DiscordTriggerEmoji $discordTriggerEmoji;
    private ClickUpAPIToken $clickUpAPIToken;
    private ClickUpListId $clickUpListId;
    readonly public ClickUpSettingMap $clickUpSettingMap;

    public function __construct(
        DiscordToken $discordToken,
        DiscordTriggerEmoji $discordTriggerEmoji,
        ClickUpAPIToken $clickUpAPIToken,
        ClickUpListId $clickUpListId,
        ClickUpSettingMap $clickUpSettingMap
    ) {
        $this->discordToken = $discordToken;
        $this->discordTriggerEmoji = $discordTriggerEmoji;
        $this->clickUpAPIToken = $clickUpAPIToken;
        $this->clickUpListId = $clickUpListId;
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

    public function getClickUpAPIToken(): ClickUpAPIToken
    {
        return $this->clickUpAPIToken;
    }

    public function getClickUpListId(): ClickUpListId
    {
        return $this->clickUpListId;
    }
}
