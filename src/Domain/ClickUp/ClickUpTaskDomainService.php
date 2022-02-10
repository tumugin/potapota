<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Potapota\Domain\Discord\DiscordMessage;

class ClickUpTaskDomainService
{
    public function createClickUpDraftTaskByDiscordMessage(DiscordMessage $discordMessage): ClickUpDraftTask
    {
        // FIXME: Stannum側にsubstrを実装する
        return new ClickUpDraftTask(
            ClickUpTaskDescription::byString(
                mb_substr($discordMessage->getDiscordMessageContent()->toString(), 0, 50)
            )
                ->concat(ClickUpTaskDescription::byString("\n"))
                ->concat($discordMessage->getDiscordAttachmentList()->toSnString()),
            ClickUpTaskDueDate::now()->addDays(7),
            ClickUpTaskName::byString($discordMessage->getDiscordMessageContent()->toString())
        );
    }
}
