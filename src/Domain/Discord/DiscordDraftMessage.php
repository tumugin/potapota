<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordDraftMessage
{
    public readonly DiscordChannelId $discordChannelId;
    public readonly DiscordMessageContent $discordMessageContent;

    public function __construct(DiscordChannelId $discordChannelId, DiscordMessageContent $discordMessageContent)
    {
        $this->discordChannelId = $discordChannelId;
        $this->discordMessageContent = $discordMessageContent;
    }
}
