<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Usecase\Discord;

use Psr\Log\LoggerInterface;
use Tumugin\Potapota\Domain\ApplicationSettings\ApplicationSettings;
use Tumugin\Potapota\Domain\BaseTask\BaseTaskDomainService;
use Tumugin\Potapota\Domain\Discord\DiscordMessage;
use Tumugin\Potapota\Domain\Discord\MessageEventRepository;
use Tumugin\Stannum\SnInteger;

class DiscordReactionReceiveAndCreateTaskUseCase
{
    private MessageEventRepository $messageEventRepository;
    private ApplicationSettings $applicationSettings;
    private LoggerInterface $logger;
    private BaseTaskDomainService $baseTaskDomainService;

    public function __construct(
        MessageEventRepository $messageEventRepository,
        ApplicationSettings $applicationSettings,
        LoggerInterface $logger,
        BaseTaskDomainService $baseTaskDomainService
    ) {
        $this->messageEventRepository = $messageEventRepository;
        $this->applicationSettings = $applicationSettings;
        $this->logger = $logger;
        $this->baseTaskDomainService = $baseTaskDomainService;
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
        $foundTriggerEmojiReaction = $discordMessage->discordReactionList
            ->findReactionByEmoji(
                $this->applicationSettings->discordTriggerEmoji->toDiscordReactionEmoji()
            );

        if (!$foundTriggerEmojiReaction) {
            $this->logger->info('Trigger emoji was not found in message.');
            return;
        }

        if ($foundTriggerEmojiReaction->discordReactionCount->isGreaterThan(SnInteger::byInt(1))) {
            $this->logger->info('Trigger emoji was reacted before.');
            return;
        }

        if (
            !$this->applicationSettings->checkDiscordGuildHasSetting(
                $discordMessage->discordGuildId
            )
        ) {
            $this->logger->error("Task service setting for this guild({$discordMessage->discordGuildId}) not found.");
            return;
        }

        // Create task and send message
        $this->baseTaskDomainService->createTaskAndSendDiscordMessage($discordMessage);
    }
}
