<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

class ApplicationSettings
{
    private DiscordToken $discordToken;
    private ClickUpAPIToken $clickUpAPIToken;
    private ClickUpTeamId $clickUpTeamId;
    private ClickUpSpaceId $clickUpSpaceId;
    private ClickUpProjectId $clickUpProjectId;
    private ClickUpListId $clickUpListId;

    public function __construct(
        DiscordToken $discordToken,
        ClickUpAPIToken $clickUpAPIToken,
        ClickUpTeamId $clickUpTeamId,
        ClickUpSpaceId $clickUpSpaceId,
        ClickUpProjectId $clickUpProjectId,
        ClickUpListId $clickUpListId
    ) {
        $this->discordToken = $discordToken;
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
