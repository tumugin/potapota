<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordDraftMessage
{
    private DiscordChannelId $discordChannelId;
    private DiscordMessageContent $discordMessageContent;

    public function __construct(DiscordChannelId $discordChannelId, DiscordMessageContent $discordMessageContent)
    {
        $this->discordChannelId = $discordChannelId;
        $this->discordMessageContent = $discordMessageContent;
    }

    public function getDiscordChannelId(): DiscordChannelId
    {
        return $this->discordChannelId;
    }

    public function getDiscordMessageContent(): DiscordMessageContent
    {
        return $this->discordMessageContent;
    }
}
