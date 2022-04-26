<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Trello;

use Tumugin\Potapota\Domain\Discord\DiscordGuildId;

interface TrelloTaskRepository
{
    public function createTrelloTask(DiscordGuildId $discordGuildId, TrelloDraftTask $trelloDraftTask): TrelloTask;
}
