<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Discord\Repository;

use Tumugin\Potapota\Domain\Discord\DiscordDraftMessage;
use Tumugin\Potapota\Domain\Discord\DiscordMessageRepository;

class DiscordMessageRepositoryImpl implements DiscordMessageRepository
{
    public function createMessage(DiscordDraftMessage $discordDraftMessage): void
    {
        // TODO: Implement createMessage() method.
    }
}
