<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Trello;

use Tumugin\Potapota\Domain\Discord\DiscordMessage;

class TrelloDraftTask
{
    public readonly TrelloTaskDescription $trelloTaskDescription;
    public readonly ?TrelloTaskDueDate $trelloTaskDueDate;
    public readonly TrelloTaskName $trelloTaskName;

    public function __construct(
        TrelloTaskDescription $trelloTaskDescription,
        ?TrelloTaskDueDate $trelloTaskDueDate,
        TrelloTaskName $trelloTaskName
    ) {
        $this->trelloTaskDescription = $trelloTaskDescription;
        $this->trelloTaskDueDate = $trelloTaskDueDate;
        $this->trelloTaskName = $trelloTaskName;
    }

    public static function createTrelloDraftTask(DiscordMessage $discordMessage): TrelloDraftTask
    {
        return new TrelloDraftTask(
            TrelloTaskDescription::byString('')
                ->concat(
                    "起票者: {$discordMessage->discordAuthor->discordAuthorName}\n"
                )
                ->concat(
                    "メッセージ: {$discordMessage->getDiscordMessageLinkUrl()}\n\n"
                )
                ->concat($discordMessage->discordMessageContent)
                ->concat("\n\n")
                ->concat($discordMessage->discordAttachmentList->toSnString()),
            TrelloTaskDueDate::now()->addDays(7),
            TrelloTaskName::byString(
                $discordMessage->discordMessageContent->toString()
            )
                ->removeUrlsFromTaskName()
                ->removeNewLineFromTaskName()
                ->shortenTaskName()
                ->addMudaiTextIfEmptyToTaskName()
        );
    }
}
