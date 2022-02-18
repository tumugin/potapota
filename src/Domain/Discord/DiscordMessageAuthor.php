<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordMessageAuthor
{
    public readonly DiscordMessageAuthorId $discordAuthorId;
    public readonly DiscordMessageAuthorName $discordAuthorName;

    public function __construct(DiscordMessageAuthorId $discordAuthorId, DiscordMessageAuthorName $discordAuthorName)
    {
        $this->discordAuthorId = $discordAuthorId;
        $this->discordAuthorName = $discordAuthorName;
    }
}
