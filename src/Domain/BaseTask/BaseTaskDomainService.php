<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\BaseTask;

use Psr\Log\LoggerInterface;
use Tumugin\Potapota\Domain\ApplicationSettings\ApplicationSettings;
use Tumugin\Potapota\Domain\ClickUp\ClickUpDraftTask;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskRepository;
use Tumugin\Potapota\Domain\Discord\DiscordDraftMessage;
use Tumugin\Potapota\Domain\Discord\DiscordMessage;
use Tumugin\Potapota\Domain\Discord\DiscordMessageDomainService;
use Tumugin\Potapota\Domain\Discord\DiscordMessageRepository;
use Tumugin\Potapota\Domain\Exceptions\PotapotaUnexpectedConditionException;
use Tumugin\Potapota\Domain\TaskServiceSelection\TaskServiceSelection;
use Tumugin\Potapota\Domain\Trello\TrelloDraftTask;
use Tumugin\Potapota\Domain\Trello\TrelloTaskRepository;

class BaseTaskDomainService
{
    private LoggerInterface $logger;
    private ClickUpTaskRepository $clickUpTaskRepository;
    private DiscordMessageDomainService $discordMessageDomainService;
    private DiscordMessageRepository $discordMessageRepository;
    private ApplicationSettings $applicationSettings;
    private TrelloTaskRepository $trelloTaskRepository;

    public function __construct(
        LoggerInterface $logger,
        ClickUpTaskRepository $clickUpTaskRepository,
        DiscordMessageDomainService $discordMessageDomainService,
        DiscordMessageRepository $discordMessageRepository,
        ApplicationSettings $applicationSettings,
        TrelloTaskRepository $trelloTaskRepository
    ) {
        $this->logger = $logger;
        $this->clickUpTaskRepository = $clickUpTaskRepository;
        $this->discordMessageDomainService = $discordMessageDomainService;
        $this->discordMessageRepository = $discordMessageRepository;
        $this->applicationSettings = $applicationSettings;
        $this->trelloTaskRepository = $trelloTaskRepository;
    }

    public function createTaskAndSendDiscordMessage(DiscordMessage $discordMessage): void
    {
        $serviceSelection = $this->applicationSettings->taskServiceSelectionSettingMap->getSettingByDiscordGuildId(
            $discordMessage->discordGuildId
        );

        $draftDiscordMessage = match ($serviceSelection) {
            TaskServiceSelection::TRELLO => $this->createTrelloTaskAndDiscordDraftMessage($discordMessage),
            TaskServiceSelection::CLICKUP => $this->createClickUpTaskAndDiscordDraftMessage($discordMessage),
            default => throw new PotapotaUnexpectedConditionException(),
        };

        // 作成されたタスクのリンクを送信する
        $this->discordMessageRepository->createMessage($draftDiscordMessage);
        $this->logger->info('Message sent.');
    }

    private function createTrelloTaskAndDiscordDraftMessage(DiscordMessage $discordMessage): DiscordDraftMessage
    {
        $createdTask = $this->trelloTaskRepository->createTrelloTask(
            $discordMessage->discordGuildId,
            TrelloDraftTask::createTrelloDraftTask($discordMessage)
        );

        $this->logger->info('Trello task created.');

        return $this->discordMessageDomainService->createDiscordDraftMessageByTrelloTask(
            $discordMessage,
            $createdTask
        );
    }

    private function createClickUpTaskAndDiscordDraftMessage(DiscordMessage $discordMessage): DiscordDraftMessage
    {
        $createdTask = $this->clickUpTaskRepository->createClickUpTask(
            $discordMessage->discordGuildId,
            ClickUpDraftTask::createClickUpDraftTaskByDiscordMessage($discordMessage)
        );

        $this->logger->info('ClickUp task created.');

        return $this->discordMessageDomainService->createDiscordDraftMessageByClickUpTask(
            $discordMessage,
            $createdTask
        );
    }
}
