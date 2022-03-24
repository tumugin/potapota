<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Discord\Repository;

use Discord\Discord;
use Discord\Parts\Channel\Attachment;
use Discord\Parts\Channel\Reaction;
use Discord\Parts\WebSockets\MessageReaction;
use Discord\WebSockets\Event;
use Psr\Log\LoggerInterface;
use React\Promise\Promise;
use Tumugin\Potapota\Domain\Discord\DiscordAttachment;
use Tumugin\Potapota\Domain\Discord\DiscordAttachmentList;
use Tumugin\Potapota\Domain\Discord\DiscordAttachmentUrl;
use Tumugin\Potapota\Domain\Discord\DiscordChannelId;
use Tumugin\Potapota\Domain\Discord\DiscordGuildId;
use Tumugin\Potapota\Domain\Discord\DiscordMessage;
use Tumugin\Potapota\Domain\Discord\DiscordMessageAuthor;
use Tumugin\Potapota\Domain\Discord\DiscordMessageAuthorId;
use Tumugin\Potapota\Domain\Discord\DiscordMessageAuthorName;
use Tumugin\Potapota\Domain\Discord\DiscordMessageContent;
use Tumugin\Potapota\Domain\Discord\DiscordMessageId;
use Tumugin\Potapota\Domain\Discord\DiscordReaction;
use Tumugin\Potapota\Domain\Discord\DiscordReactionCount;
use Tumugin\Potapota\Domain\Discord\DiscordReactionEmoji;
use Tumugin\Potapota\Domain\Discord\DiscordReactionList;
use Tumugin\Potapota\Domain\Discord\MessageEventRepository;
use Tumugin\Potapota\Domain\Exceptions\PotapotaUnexpectedConditionException;
use Tumugin\Potapota\Infra\ExceptionLogger\ExceptionLogger;
use Tumugin\Stannum\SnList;

class MessageEventRepositoryImpl implements MessageEventRepository
{
    private Discord $discord;
    private LoggerInterface $logger;
    private ExceptionLogger $exceptionLogger;

    public function __construct(Discord $discord, LoggerInterface $logger, ExceptionLogger $exceptionLogger)
    {
        $this->discord = $discord;
        $this->logger = $logger;
        $this->exceptionLogger = $exceptionLogger;
    }

    public function onEmojiReactionEvent(callable $onEmojiReactionEvent): void
    {
        $this->discord->on(
            Event::MESSAGE_REACTION_ADD,
            function (MessageReaction $reaction) use (&$onEmojiReactionEvent) {
                $this->onEmojiReactionEventHandler($reaction, $onEmojiReactionEvent)
                    ->otherwise(
                        fn(\Exception $ex) => $this->exceptionLogger->logExceptionError($ex)
                    )
                    ->done();
            }
        );
    }

    private function onEmojiReactionEventHandler(
        MessageReaction $reaction,
        callable $onEmojiReactionEvent
    ): Promise {
        /**
         * @var Promise $result
         */
        $result = $reaction->fetch()
            ->then(function (MessageReaction $reaction) use (&$onEmojiReactionEvent) {
                $this->logger->info('Loaded message.');
                $onEmojiReactionEvent($this->processMessageReaction($reaction));
            });
        return $result;
    }

    private function processMessageReaction(MessageReaction $reaction): DiscordMessage
    {
        if ($reaction->isPartial()) {
            throw new \RuntimeException('message should not be partial.');
        }

        $message = $reaction->message
            ?: throw new PotapotaUnexpectedConditionException('message should not be null.');

        $convertedReactions = $message
            ->reactions
            ->map(fn(Reaction $reaction) => new DiscordReaction(
                DiscordReactionEmoji::byString($reaction->emoji->name),
                DiscordReactionCount::byInt($reaction->count),
            ));

        $convertedAttachment = SnList::byArray($message->attachments->toArray())
            ->map(
                fn(Attachment $rawAttachment) => new DiscordAttachment(
                    DiscordAttachmentUrl::byString($rawAttachment->url)
                )
            );

        // FIXME: なぜかURLだけ含むメッセージはmessage->contentがnullになるケースがあるので塞ぐ
        return new DiscordMessage(
            DiscordChannelId::byString($reaction->channel_id),
            DiscordMessageContent::byString(
                $message->content ?? ''
            ),
            DiscordMessageId::byString($reaction->message_id),
            new DiscordMessageAuthor(
                DiscordMessageAuthorId::byString(
                    $message->author?->id ?: throw new PotapotaUnexpectedConditionException(
                        'message author should not be null.'
                    )
                ),
                DiscordMessageAuthorName::byString(
                    $message->author?->username ?: throw new PotapotaUnexpectedConditionException(
                        'message author should not be null.'
                    )
                ),
            ),
            DiscordAttachmentList::byArray($convertedAttachment->toArray()),
            DiscordReactionList::byArray($convertedReactions->toArray()),
            DiscordGuildId::byString(
                $message->guild_id ?: throw new PotapotaUnexpectedConditionException(
                    'guild_id should not be null.'
                )
            )
        );
    }
}
