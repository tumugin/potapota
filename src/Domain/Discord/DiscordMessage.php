<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordMessage
{
    public readonly DiscordChannelId $discordChannelId;
    public readonly DiscordMessageContent $discordMessageContent;
    public readonly DiscordMessageId $discordMessageId;
    public readonly DiscordMessageAuthor $discordAuthor;
    public readonly DiscordAttachmentList $discordAttachmentList;
    public readonly DiscordReactionList $discordReactionList;
    public readonly DiscordGuildId $discordGuildId;

    public function __construct(
        DiscordChannelId $discordChannelId,
        DiscordMessageContent $discordMessageContent,
        DiscordMessageId $discordMessageId,
        DiscordMessageAuthor $discordAuthor,
        DiscordAttachmentList $discordAttachmentList,
        DiscordReactionList $discordReactionList,
        DiscordGuildId $discordGuildId
    ) {
        $this->discordChannelId = $discordChannelId;
        $this->discordMessageContent = $discordMessageContent;
        $this->discordMessageId = $discordMessageId;
        $this->discordAuthor = $discordAuthor;
        $this->discordAttachmentList = $discordAttachmentList;
        $this->discordReactionList = $discordReactionList;
        $this->discordGuildId = $discordGuildId;
    }

    public function getDiscordMessageLinkUrl(): DiscordMessageLinkUrl
    {
        return DiscordMessageLinkUrl::byString(
            "https://discord.com/channels/{$this->discordGuildId}/{$this->discordChannelId}/{$this->discordMessageId}"
        );
    }
}
