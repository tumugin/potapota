<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Discord\Repository;

use Discord\Discord;
use Tumugin\Potapota\Domain\Discord\DiscordDraftMessage;
use Tumugin\Potapota\Domain\Discord\DiscordMessageRepository;

class DiscordMessageRepositoryImpl implements DiscordMessageRepository
{
    private Discord $discord;

    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    public function createMessage(DiscordDraftMessage $discordDraftMessage): void
    {
        $channel = $this->discord->getChannel($discordDraftMessage->getDiscordChannelId()->toString());
        $channel->sendMessage($discordDraftMessage->getDiscordMessageContent()->toString());
    }
}
