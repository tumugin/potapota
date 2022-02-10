<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;

class DiscordMessageDomainService
{
    public function createDiscordDraftMessageByClickUpTask(
        DiscordChannelId $discordChannelId,
        ClickUpTask $clickUpTask
    ): DiscordDraftMessage {
        $taskUrl = $clickUpTask->getClickUpTaskUrl()->toString();

        return new DiscordDraftMessage(
            $discordChannelId,
            DiscordMessageContent::byString("タスクを作成しました！\n{$taskUrl}")
        );
    }
}
