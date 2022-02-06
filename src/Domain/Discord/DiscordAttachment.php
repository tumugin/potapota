<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordAttachment
{
    private DiscordAttachmentUrl $discordAttachmentUrl;

    public function __construct(DiscordAttachmentUrl $discordAttachmentUrl)
    {
        $this->discordAttachmentUrl = $discordAttachmentUrl;
    }

    public function getDiscordAttachmentUrl(): DiscordAttachmentUrl
    {
        return $this->discordAttachmentUrl;
    }
}
