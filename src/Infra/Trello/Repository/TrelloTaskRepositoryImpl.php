<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Trello\Repository;

use Tumugin\Potapota\Domain\Discord\DiscordGuildId;
use Tumugin\Potapota\Domain\Trello\TrelloDraftTask;
use Tumugin\Potapota\Domain\Trello\TrelloTask;
use Tumugin\Potapota\Domain\Trello\TrelloTaskRepository;

class TrelloTaskRepositoryImpl implements TrelloTaskRepository
{
    public function createTrelloTask(DiscordGuildId $discordGuildId, TrelloDraftTask $trelloDraftTask): TrelloTask
    {
        throw new \RuntimeException('not implemented');
    }
}
