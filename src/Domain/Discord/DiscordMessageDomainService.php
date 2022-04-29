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
        $taskUrl = $clickUpTask->clickUpTaskUrl->toString();
        $taskTitle = $clickUpTask->clickUpTaskName->toString();

        return new DiscordDraftMessage(
            $discordMessage->discordChannelId,
            DiscordMessageContent::byString(
                "ClickUpのタスクを作ったよ～～～！！！\n" .
                "ClickUpのタスクのタイトルは「{$taskTitle}」だよ～～～！\n" .
                "ちゃんとやらないとあおいすずに怒られるぞ～～\n\n" .
                "ClickUpタスク: {$taskUrl}\n" .
                "元メッセージ: {$discordMessage->getDiscordMessageLinkUrl()}"
            )
        );
    }

    public function createDiscordDraftMessageByTrelloTask(
        DiscordMessage $discordMessage,
        TrelloTask $trelloTask
    ): DiscordDraftMessage {
        $taskUrl = $trelloTask->trelloTaskUrl->toString();
        $taskTitle = $trelloTask->trelloTaskName->toString();

        return new DiscordDraftMessage(
            $discordMessage->discordChannelId,
            DiscordMessageContent::byString(
                "Trelloのカードを作ったよ～～～！！！\n" .
                "Trelloのカードのタイトルは「{$taskTitle}」だよ～～～！\n" .
                "ちゃんとやらないとあおいすずに怒られるぞ～～\n\n" .
                "Trelloカード: {$taskUrl}\n" .
                "元メッセージ: {$discordMessage->getDiscordMessageLinkUrl()}"
            )
        );
    }
}
