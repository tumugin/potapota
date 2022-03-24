<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Discord\Repository;

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Tumugin\Potapota\Domain\Discord\DiscordDraftMessage;
use Tumugin\Potapota\Domain\Discord\DiscordMessageRepository;
use Tumugin\Potapota\Domain\Exceptions\PotapotaUnexpectedConditionException;

class DiscordMessageRepositoryImpl implements DiscordMessageRepository
{
    private Discord $discord;

    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    public function createMessage(DiscordDraftMessage $discordDraftMessage): void
    {
        $channel = $this->discord->getChannel($discordDraftMessage->discordChannelId->toString());

        if ($channel === null) {
            throw new PotapotaUnexpectedConditionException('$channel cannot be null.');
        }

        $channel->sendMessage(
            MessageBuilder::new()->setContent($discordDraftMessage->discordMessageContent->toString())
        )->done();
    }
}
