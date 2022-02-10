<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

interface DiscordEventRepository
{
    public function onDiscordReadyEvent(callable $onDiscordReady): void;
}
