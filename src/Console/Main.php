<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Console;

use Psr\Log\LoggerInterface;
use Tumugin\Potapota\Domain\Discord\DiscordEventRepository;
use Tumugin\Potapota\Infra\Application\ErrorReporter;
use Tumugin\Potapota\Usecase\Discord\DiscordReactionReceiveAndCreateTaskUseCase;

class Main
{
    private LoggerInterface $logger;
    private DiscordReactionReceiveAndCreateTaskUseCase $discordReactionReceiveAndCreateTaskUseCase;
    private DiscordEventRepository $discordEventRepository;
    private ErrorReporter $errorReporter;

    public function __construct(
        LoggerInterface $logger,
        DiscordReactionReceiveAndCreateTaskUseCase $discordReactionReceiveAndCreateTaskUseCase,
        DiscordEventRepository $discordEventRepository,
        ErrorReporter $errorReporter
    ) {
        $this->logger = $logger;
        $this->discordReactionReceiveAndCreateTaskUseCase = $discordReactionReceiveAndCreateTaskUseCase;
        $this->discordEventRepository = $discordEventRepository;
        $this->errorReporter = $errorReporter;
    }

    public function execute(): void
    {
        $this->logger->info("Potapota started.");
        $this->errorReporter->registerErrorReporter();

        $this->discordEventRepository->onDiscordReadyEvent(
            function () {
                $this->logger->info('Potapota Discord ready.');
                $this->discordReactionReceiveAndCreateTaskUseCase->listenOnReceiveEmoji();
            }
        );
    }
}
