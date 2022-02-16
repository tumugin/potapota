<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Discord\Repository;

use Discord\Discord;
use Discord\WebSockets\Event;
use Tumugin\Potapota\Domain\Discord\DiscordEventRepository;

class DiscordEventRepositoryImpl implements DiscordEventRepository
{
    private Discord $discord;

    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    public function onDiscordReadyEvent(callable $onDiscordReady): void
    {
        $this->discord->on('ready', function () use ($onDiscordReady) {
            $onDiscordReady();
        });
        $this->discord->run();
    }
}
