<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

use Tumugin\Stannum\SnList;

/**
 * @extends SnList<DiscordReaction>
 */
class DiscordReactionList extends SnList
{
    public function findReactionByEmoji(DiscordReactionEmoji $discordReactionEmoji): ?DiscordReaction
    {
        return $this->find(
            fn(DiscordReaction $discordReaction) => $discordReaction->discordReactionEmoji->equals(
                $discordReactionEmoji
            )
        );
    }
}
