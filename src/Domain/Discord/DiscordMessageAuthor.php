<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordMessageAuthor
{
    private DiscordMessageAuthorId $discordAuthorId;
    private DiscordMessageAuthorName $discordAuthorName;

    public function __construct(DiscordMessageAuthorId $discordAuthorId, DiscordMessageAuthorName $discordAuthorName)
    {
        $this->discordAuthorId = $discordAuthorId;
        $this->discordAuthorName = $discordAuthorName;
    }

    public function getDiscordAuthorId(): DiscordMessageAuthorId
    {
        return $this->discordAuthorId;
    }

    public function getDiscordAuthorName(): DiscordMessageAuthorName
    {
        return $this->discordAuthorName;
    }
}
