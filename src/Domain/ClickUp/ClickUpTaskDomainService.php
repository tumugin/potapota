<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Potapota\Domain\Discord\DiscordMessage;
use Tumugin\Potapota\Domain\Exceptions\NotImplementedException;

class ClickUpTaskDomainService
{
    public function createClickUpDraftTaskByDiscordMessage(DiscordMessage $discordMessage): ClickUpDraftTask
    {
        throw new NotImplementedException('ClickUpTaskDomainService not implemented.');
    }
}
