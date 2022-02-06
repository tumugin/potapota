<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

interface DiscordMessageRepository
{
    public function createMessage(DiscordDraftMessage $discordDraftMessage): void;
}
