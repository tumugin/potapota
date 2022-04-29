<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;
use Tumugin\Potapota\Domain\Trello\TrelloTask;

class DiscordMessageDomainService
{
    public function createDiscordDraftMessageByClickUpTask(
        DiscordMessage $discordMessage,
        ClickUpTask $clickUpTask
    ): DiscordDraftMessage {
        return new DiscordDraftMessage(
            $discordMessage->discordChannelId,
            DiscordMessageContent::byString(
                "ClickUpのタスクを作ったよ～～～！！！\n" .
                "ClickUpのタスクのタイトルは「{$clickUpTask->clickUpTaskName}」だよ～～～！\n" .
                ($clickUpTask->clickUpTaskDueDate ?
                    "ちゃんと{$clickUpTask->clickUpTaskDueDate->formatToShortDate()}までにやらないとあおいすずに怒られるぞ～～\n\n" :
                    "ちゃんとやらないとあおいすずに怒られるぞ～～\n\n") .
                "ClickUpタスク: {$clickUpTask->clickUpTaskUrl}\n" .
                "元メッセージ: {$discordMessage->getDiscordMessageLinkUrl()}"
            )
        );
    }

    public function createDiscordDraftMessageByTrelloTask(
        DiscordMessage $discordMessage,
        TrelloTask $trelloTask
    ): DiscordDraftMessage {
        return new DiscordDraftMessage(
            $discordMessage->discordChannelId,
            DiscordMessageContent::byString(
                "Trelloのカードを作ったよ～～～！！！\n" .
                "Trelloのカードのタイトルは「{$trelloTask->trelloTaskName}」だよ～～～！\n" .
                ($trelloTask->trelloTaskDueDate ?
                    "ちゃんと{$trelloTask->trelloTaskDueDate->formatToShortDate()}までにやらないとあおいすずに怒られるぞ～～\n\n" :
                    "ちゃんとやらないとあおいすずに怒られるぞ～～\n\n") .
                "Trelloカード: {$trelloTask->trelloTaskUrl}\n" .
                "元メッセージ: {$discordMessage->getDiscordMessageLinkUrl()}"
            )
        );
    }
}
