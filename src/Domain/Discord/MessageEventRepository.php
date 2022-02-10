<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

interface MessageEventRepository
{
    /**
     * @param callable(DiscordMessage):void $onEmojiReactionEvent
     */
    public function onEmojiReactionEvent(callable $onEmojiReactionEvent): void;
}
