<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Potapota\Domain\Discord\DiscordMessage;
use Tumugin\Stannum\SnInteger;

class ClickUpTaskDomainService
{
    public function createClickUpDraftTaskByDiscordMessage(DiscordMessage $discordMessage): ClickUpDraftTask
    {
        return new ClickUpDraftTask(
            ClickUpTaskDescription::byString(
                $discordMessage->getDiscordMessageContent()->toString()
            )
                ->concat(ClickUpTaskDescription::byString("\n"))
                ->concat($discordMessage->getDiscordAttachmentList()->toSnString()),
            ClickUpTaskDueDate::now()->addDays(7),
            ClickUpTaskName::byString(
                $discordMessage->getDiscordMessageContent()
                    ->take(SnInteger::byInt(50))
                    ->toString()
            )
        );
    }
}
