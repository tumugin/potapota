<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Potapota\Domain\Discord\DiscordGuildId;

interface ClickUpTaskRepository
{
    public function createClickUpTask(DiscordGuildId $discordGuildId, ClickUpDraftTask $clickUpDraftTask): ClickUpTask;
}
