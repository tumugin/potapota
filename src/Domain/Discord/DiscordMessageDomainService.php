<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;

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
                "タスクを作ったよ～～～！！！\n" .
                "タスクのタイトルは「{$taskTitle}」だよ～～～！\n" .
                "ちゃんとやらないとあおいすずに怒られるぞ～～\n\n" .
                "ClickUp: {$taskUrl}\n" .
                "元メッセージ: {$discordMessage->getDiscordMessageLinkUrl()}"
            )
        );
    }
}
