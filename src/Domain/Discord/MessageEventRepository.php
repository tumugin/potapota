<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

interface MessageEventRepository
{
    /**
     * @param DiscordReactionEmoji $discordEmoji
     * @param callable(DiscordMessage):void $onEmojiReactionEvent
     */
    public function onEmojiReactionEvent(DiscordReactionEmoji $discordEmoji, callable $onEmojiReactionEvent): void;
}
