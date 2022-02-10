<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Console;

use Psr\Log\LoggerInterface;
use Tumugin\Potapota\Domain\Discord\DiscordEventRepository;
use Tumugin\Potapota\Usecase\Discord\DiscordReactionReceiveAndCreateTaskUseCase;

class Main
{
    private LoggerInterface $logger;
    private DiscordReactionReceiveAndCreateTaskUseCase $discordReactionReceiveAndCreateTaskUseCase;
    private DiscordEventRepository $discordEventRepository;

    public function __construct(
        LoggerInterface $logger,
        DiscordReactionReceiveAndCreateTaskUseCase $discordReactionReceiveAndCreateTaskUseCase,
        DiscordEventRepository $discordEventRepository
    ) {
        $this->logger = $logger;
        $this->discordReactionReceiveAndCreateTaskUseCase = $discordReactionReceiveAndCreateTaskUseCase;
        $this->discordEventRepository = $discordEventRepository;
    }

    public function execute()
    {
        $this->logger->info("Potapota started.");

        $this->discordEventRepository->onDiscordReadyEvent(function () {
            $this->discordReactionReceiveAndCreateTaskUseCase->listenOnReceiveEmoji();
        });
    }
}
