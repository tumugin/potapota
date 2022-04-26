<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

use Tumugin\Stannum\SnString;

class DiscordTriggerEmoji extends SnString
{
    public function toDiscordReactionEmoji(): DiscordReactionEmoji
    {
        return DiscordReactionEmoji::byString($this->toString());
    }
}
