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
                "タスクを作成しました！\n" .
                "タスクのタイトルは「{$taskTitle}」です。\n" .
                "ClickUp: {$taskUrl}\n" .
                "元メッセージ: {$discordMessage->getDiscordMessageLinkUrl()}"
            )
        );
    }
}
