<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordReaction
{
    public readonly DiscordReactionEmoji $discordReactionEmoji;
    public readonly DiscordReactionCount $discordReactionCount;

    public function __construct(DiscordReactionEmoji $discordReactionEmoji, DiscordReactionCount $discordReactionCount)
    {
        $this->discordReactionEmoji = $discordReactionEmoji;
        $this->discordReactionCount = $discordReactionCount;
    }
}
