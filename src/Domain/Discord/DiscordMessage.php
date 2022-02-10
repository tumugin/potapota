<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordMessage
{
    private DiscordChannelId $discordChannelId;
    private DiscordMessageContent $discordMessageContent;
    private DiscordMessageId $discordMessageId;
    private DiscordMessageAuthor $discordAuthor;
    private DiscordAttachmentList $discordAttachmentList;
    private DiscordReactionList $discordReactionList;

    public function __construct(
        DiscordChannelId $discordChannelId,
        DiscordMessageContent $discordMessageContent,
        DiscordMessageId $discordMessageId,
        DiscordMessageAuthor $discordAuthor,
        DiscordAttachmentList $discordAttachmentList,
        DiscordReactionList $discordReactionList
    ) {
        $this->discordChannelId = $discordChannelId;
        $this->discordMessageContent = $discordMessageContent;
        $this->discordMessageId = $discordMessageId;
        $this->discordAuthor = $discordAuthor;
        $this->discordAttachmentList = $discordAttachmentList;
        $this->discordReactionList = $discordReactionList;
    }

    public function getDiscordChannelId(): DiscordChannelId
    {
        return $this->discordChannelId;
    }

    public function getDiscordMessageContent(): DiscordMessageContent
    {
        return $this->discordMessageContent;
    }

    public function getDiscordMessageId(): DiscordMessageId
    {
        return $this->discordMessageId;
    }

    public function getDiscordAuthor(): DiscordMessageAuthor
    {
        return $this->discordAuthor;
    }

    public function getDiscordAttachmentList(): DiscordAttachmentList
    {
        return $this->discordAttachmentList;
    }

    public function getDiscordReactionList(): DiscordReactionList
    {
        return $this->discordReactionList;
    }
}
