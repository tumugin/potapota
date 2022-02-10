<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordReaction
{
    private DiscordReactionEmoji $discordReactionEmoji;
    private DiscordReactionCount $discordReactionCount;

    public function __construct(DiscordReactionEmoji $discordReactionEmoji, DiscordReactionCount $discordReactionCount)
    {
        $this->discordReactionEmoji = $discordReactionEmoji;
        $this->discordReactionCount = $discordReactionCount;
    }

    public function getDiscordReactionEmoji(): DiscordReactionEmoji
    {
        return $this->discordReactionEmoji;
    }

    public function getDiscordReactionCount(): DiscordReactionCount
    {
        return $this->discordReactionCount;
    }
}
