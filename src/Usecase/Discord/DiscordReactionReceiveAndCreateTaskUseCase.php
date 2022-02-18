<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Usecase\Discord;

use Psr\Log\LoggerInterface;
use Tumugin\Potapota\Domain\Application\ApplicationSettings;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDomainService;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskRepository;
use Tumugin\Potapota\Domain\Discord\DiscordMessage;
use Tumugin\Potapota\Domain\Discord\DiscordMessageDomainService;
use Tumugin\Potapota\Domain\Discord\DiscordMessageRepository;
use Tumugin\Potapota\Domain\Discord\MessageEventRepository;
use Tumugin\Stannum\SnInteger;

class DiscordReactionReceiveAndCreateTaskUseCase
{
    private MessageEventRepository $messageEventRepository;
    private ApplicationSettings $applicationSettings;
    private ClickUpTaskRepository $clickUpTaskRepository;
    private ClickUpTaskDomainService $clickUpTaskDomainService;
    private DiscordMessageDomainService $discordMessageDomainService;
    private DiscordMessageRepository $discordMessageRepository;
    private LoggerInterface $logger;

    public function __construct(
        MessageEventRepository $messageEventRepository,
        ApplicationSettings $applicationSettings,
        ClickUpTaskRepository $clickUpTaskRepository,
        ClickUpTaskDomainService $clickUpTaskDomainService,
        DiscordMessageDomainService $discordMessageDomainService,
        DiscordMessageRepository $discordMessageRepository,
        LoggerInterface $logger
    ) {
        $this->messageEventRepository = $messageEventRepository;
        $this->applicationSettings = $applicationSettings;
        $this->clickUpTaskRepository = $clickUpTaskRepository;
        $this->clickUpTaskDomainService = $clickUpTaskDomainService;
        $this->discordMessageDomainService = $discordMessageDomainService;
        $this->discordMessageRepository = $discordMessageRepository;
        $this->logger = $logger;
    }

    public function listenOnReceiveEmoji(): void
    {
        $this->messageEventRepository->onEmojiReactionEvent(
            function (DiscordMessage $discordMessage) {
                $this->logger->info('Received emoji reaction event.');
                $this->onReceiveEmoji($discordMessage);
            }
        );
    }

    public function onReceiveEmoji(DiscordMessage $discordMessage): void
    {
        // triggerになるemojiにリアクションが見つからない場合もしくは1つより多く付いている場合は無視する
        $foundTriggerEmojiReaction = $discordMessage->getDiscordReactionList()
            ->findReactionByEmoji(
                $this->applicationSettings->getDiscordTriggerEmoji()->toDiscordReactionEmoji()
            );

        if (!$foundTriggerEmojiReaction) {
            $this->logger->info('Trigger emoji was not found in message.');
            return;
        }

        if ($foundTriggerEmojiReaction->getDiscordReactionCount()->isGreaterThan(SnInteger::byInt(1))) {
            $this->logger->info('Trigger emoji was reacted before.');
            return;
        }

        if (
            !$this->applicationSettings->clickUpSettingMap->settingsOfDiscordGuildIdExists(
                $discordMessage->discordGuildId
            )
        ) {
            $this->logger->error("ClickUp setting for this guild({$discordMessage->discordGuildId}) not found.");
            return;
        }

        // タスクを作成する
        $createdTask = $this->clickUpTaskRepository->createClickUpTask(
            $discordMessage->discordGuildId,
            $this->clickUpTaskDomainService->createClickUpDraftTaskByDiscordMessage($discordMessage)
        );
        $this->logger->info('ClickUp task created.');

        // 作成されたタスクのリンクを送信する
        $this->discordMessageRepository->createMessage(
            $this->discordMessageDomainService->createDiscordDraftMessageByClickUpTask(
                $discordMessage->getDiscordChannelId(),
                $createdTask
            )
        );
        $this->logger->info('Message sent.');
    }
}
