<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Console;

use Discord\Discord;
use Discord\WebSockets\Event;
use Psr\Log\LoggerInterface;
use Tumugin\Potapota\Usecase\Discord\DiscordReactionReceiveAndCreateTaskUseCase;

class Main
{
    private LoggerInterface $logger;
    private Discord $discord;
    private DiscordReactionReceiveAndCreateTaskUseCase $discordReactionReceiveAndCreateTaskUseCase;

    public function __construct(
        LoggerInterface $logger,
        Discord $discord,
        DiscordReactionReceiveAndCreateTaskUseCase $discordReactionReceiveAndCreateTaskUseCase
    ) {
        $this->logger = $logger;
        $this->discord = $discord;
        $this->discordReactionReceiveAndCreateTaskUseCase = $discordReactionReceiveAndCreateTaskUseCase;
    }

    public function execute()
    {
        $this->logger->info("Potapota started.");

        // FIXME: イベントループを始めるのに一旦ここから直接呼び出している
        $this->discord->on(Event::READY, function () {
            $this->discordReactionReceiveAndCreateTaskUseCase->listenOnReceiveEmoji();
        });
    }
}
