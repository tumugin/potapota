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
    private DiscordReactionEmojiList $discordReactionEmojiList;

    public function __construct(
        DiscordChannelId $discordChannelId,
        DiscordMessageContent $discordMessageContent,
        DiscordMessageId $discordMessageId,
        DiscordMessageAuthor $discordAuthor,
        DiscordAttachmentList $discordAttachmentList,
        DiscordReactionEmojiList $discordReactionEmojiList
    ) {
        $this->discordChannelId = $discordChannelId;
        $this->discordMessageContent = $discordMessageContent;
        $this->discordMessageId = $discordMessageId;
        $this->discordAuthor = $discordAuthor;
        $this->discordAttachmentList = $discordAttachmentList;
        $this->discordReactionEmojiList = $discordReactionEmojiList;
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

    public function getDiscordReactionEmojiList(): DiscordReactionEmojiList
    {
        return $this->discordReactionEmojiList;
    }
}
