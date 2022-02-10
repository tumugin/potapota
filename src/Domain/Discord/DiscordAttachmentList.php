<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

use Tumugin\Stannum\SnList;
use Tumugin\Stannum\SnString;

class DiscordAttachmentList extends SnList
{
    public function toSnString(): SnString
    {
        $mappedStrings = $this->map(
            fn(DiscordAttachment $discordAttachment) => $discordAttachment->getDiscordAttachmentUrl()->toString()
        )
            ->toArray();

        // FIXME: Stannum側にimplodeを実装する
        return SnString::byString(implode("\n", $mappedStrings));
    }
}
