<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Potapota\Domain\Discord\DiscordMessage;

class ClickUpTaskDomainService
{
    public function createClickUpDraftTaskByDiscordMessage(DiscordMessage $discordMessage): ClickUpDraftTask
    {
        return new ClickUpDraftTask(
            ClickUpTaskDescription::byString(
                '起票者: ' . $discordMessage->discordAuthor->discordAuthorName->toString() . "\n"
            )
                ->concat($discordMessage->discordMessageContent)
                ->concat(ClickUpTaskDescription::byString("\n\n"))
                ->concat($discordMessage->discordAttachmentList->toSnString()),
            ClickUpTaskDueDate::now()->addDays(7),
            ClickUpTaskName::byString(
                $discordMessage->discordMessageContent->toString()
            )
                ->removeUrlsFromTaskName()
                ->removeNewLine()
                ->shortenTaskName()
        );
    }
}
