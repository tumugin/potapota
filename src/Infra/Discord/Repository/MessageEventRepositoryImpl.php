<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Discord\Repository;

use Discord\Discord;
use Discord\Parts\Channel\Reaction;
use Discord\Parts\WebSockets\MessageReaction;
use Discord\WebSockets\Event;
use Psr\Log\LoggerInterface;
use React\Promise\PromiseInterface;
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
    ): PromiseInterface {
        return $reaction->fetch()
            ->then(function (MessageReaction $reaction) use (&$onEmojiReactionEvent) {
                $this->logger->info('Loaded message.');
                $onEmojiReactionEvent($this->processMessageReaction($reaction));
            });
    }

    private function processMessageReaction(MessageReaction $reaction): DiscordMessage
    {
        if ($reaction->isPartial()) {
            throw new \RuntimeException('message should not be partial.');
        }

        $convertedReactionsArray = $reaction->message
            ->reactions
            ->map(fn(Reaction $reaction) => new DiscordReaction(
                DiscordReactionEmoji::byString($reaction->emoji->name),
                DiscordReactionCount::byInt($reaction->count),
            ))
            ->toArray();

        $convertedAttachmentArray = SnList::byArray($reaction->message->attachments)
            ->map(
                fn(\stdClass $rawAttachment) => new DiscordAttachment(
                    DiscordAttachmentUrl::byString($rawAttachment->url)
                )
            )
            ->toArray();

        // FIXME: なぜかURLだけ含むメッセージはmessage->contentがnullになるケースがあるので塞ぐ
        return new DiscordMessage(
            DiscordChannelId::byString($reaction->channel_id),
            DiscordMessageContent::byString(
                $reaction->message->content ?? ''
            ),
            DiscordMessageId::byString($reaction->message_id),
            new DiscordMessageAuthor(
                DiscordMessageAuthorId::byString($reaction->message->author->id),
                DiscordMessageAuthorName::byString($reaction->message->author->username),
            ),
            DiscordAttachmentList::byArray($convertedAttachmentArray),
            DiscordReactionList::byArray($convertedReactionsArray),
            DiscordGuildId::byString($reaction->guild_id)
        );
    }
}
