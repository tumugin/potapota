<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

use Tumugin\Stannum\SnList;
use Tumugin\Stannum\SnList\SnStringList;
use Tumugin\Stannum\SnString;

class DiscordAttachmentList extends SnList
{
    public function toSnString(): SnString
    {
        $mappedStringList = SnStringList::byArray(
            $this->map(
                fn(DiscordAttachment $discordAttachment) => $discordAttachment
                    ->getDiscordAttachmentUrl()
                    ->toString()
            )->toArray()
        );

        return $mappedStringList->joinToString(SnString::byString("\n"));
    }
}
