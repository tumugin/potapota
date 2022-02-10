<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Potapota\Domain\Discord\DiscordMessage;

class ClickUpTaskDomainService
{
    public function createClickUpDraftTaskByDiscordMessage(DiscordMessage $discordMessage): ClickUpDraftTask
    {
        return new ClickUpDraftTask(
            ClickUpTaskDescription::byString($discordMessage->getDiscordMessageContent()->toString()),
            ClickUpTaskDueDate::now()->addDays(7),
            ClickUpTaskName::byString($discordMessage->getDiscordMessageContent()->toString())
        );
    }
}
