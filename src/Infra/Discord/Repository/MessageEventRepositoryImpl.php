<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Discord\Repository;

use Tumugin\Potapota\Domain\Discord\DiscordReactionEmoji;
use Tumugin\Potapota\Domain\Discord\MessageEventRepository;

class MessageEventRepositoryImpl implements MessageEventRepository
{
    public function onEmojiReactionEvent(DiscordReactionEmoji $discordEmoji, callable $onEmojiReactionEvent): void
    {
        // TODO: Implement onEmojiReactionEvent() method.
    }
}
