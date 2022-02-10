<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

class ApplicationSettings
{
    private DiscordToken $discordToken;
    private DiscordTriggerEmoji $discordTriggerEmoji;
    private ClickUpAPIToken $clickUpAPIToken;
    private ClickUpTeamId $clickUpTeamId;
    private ClickUpSpaceId $clickUpSpaceId;
    private ClickUpProjectId $clickUpProjectId;
    private ClickUpListId $clickUpListId;

    public function __construct(
        DiscordToken $discordToken,
        DiscordTriggerEmoji $discordTriggerEmoji,
        ClickUpAPIToken $clickUpAPIToken,
        ClickUpTeamId $clickUpTeamId,
        ClickUpSpaceId $clickUpSpaceId,
        ClickUpProjectId $clickUpProjectId,
        ClickUpListId $clickUpListId
    ) {
        $this->discordToken = $discordToken;
        $this->discordTriggerEmoji = $discordTriggerEmoji;
        $this->clickUpAPIToken = $clickUpAPIToken;
        $this->clickUpTeamId = $clickUpTeamId;
        $this->clickUpSpaceId = $clickUpSpaceId;
        $this->clickUpProjectId = $clickUpProjectId;
        $this->clickUpListId = $clickUpListId;
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

    public function getClickUpTeamId(): ClickUpTeamId
    {
        return $this->clickUpTeamId;
    }

    public function getClickUpSpaceId(): ClickUpSpaceId
    {
        return $this->clickUpSpaceId;
    }

    public function getClickUpProjectId(): ClickUpProjectId
    {
        return $this->clickUpProjectId;
    }

    public function getClickUpListId(): ClickUpListId
    {
        return $this->clickUpListId;
    }
}
