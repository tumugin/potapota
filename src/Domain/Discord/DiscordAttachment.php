<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

class DiscordAttachment
{
    public readonly DiscordAttachmentUrl $discordAttachmentUrl;

    public function __construct(DiscordAttachmentUrl $discordAttachmentUrl)
    {
        $this->discordAttachmentUrl = $discordAttachmentUrl;
    }
}
