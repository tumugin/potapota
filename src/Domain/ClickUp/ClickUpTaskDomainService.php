<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Potapota\Domain\Discord\DiscordMessage;
use Tumugin\Stannum\SnString;

class ClickUpTaskDomainService
{
    public function createClickUpDraftTaskByDiscordMessage(DiscordMessage $discordMessage): ClickUpDraftTask
    {
        return new ClickUpDraftTask(
            ClickUpTaskDescription::byString('')
                ->concat(
                    SnString::byString(
                        "起票者: {$discordMessage->discordAuthor->discordAuthorName}\n"
                    )
                )
                ->concat(
                    SnString::byString(
                        "メッセージ: {$discordMessage->getDiscordMessageLinkUrl()}\n\n"
                    )
                )
                ->concat($discordMessage->discordMessageContent)
                ->concat(ClickUpTaskDescription::byString("\n\n"))
                ->concat($discordMessage->discordAttachmentList->toSnString()),
            ClickUpTaskDueDate::now()->addDays(7),
            ClickUpTaskName::byString(
                $discordMessage->discordMessageContent->toString()
            )
                ->removeUrlsFromTaskName()
                ->removeNewLineFromTaskName()
                ->shortenTaskName()
                ->addMudaiTextIfEmptyToTaskName()
        );
    }
}
