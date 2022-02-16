<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Discord\Repository;

use Discord\Discord;
use Discord\Parts\Channel\Reaction;
use Discord\Parts\WebSockets\MessageReaction;
use Discord\WebSockets\Event;
use Psr\Log\LoggerInterface;
use Tumugin\Potapota\Domain\Discord\DiscordAttachment;
use Tumugin\Potapota\Domain\Discord\DiscordAttachmentList;
use Tumugin\Potapota\Domain\Discord\DiscordAttachmentUrl;
use Tumugin\Potapota\Domain\Discord\DiscordChannelId;
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
use Tumugin\Stannum\SnList;

class MessageEventRepositoryImpl implements MessageEventRepository
{
    private Discord $discord;
    private LoggerInterface $logger;

    public function __construct(Discord $discord, LoggerInterface $logger)
    {
        $this->discord = $discord;
        $this->logger = $logger;
    }

    public function onEmojiReactionEvent(callable $onEmojiReactionEvent): void
    {
        $this->discord->on(
            Event::MESSAGE_REACTION_ADD,
            function (MessageReaction $reaction) use (&$onEmojiReactionEvent) {
                $reaction->fetch()->then(function (MessageReaction $reaction) use (&$onEmojiReactionEvent) {
                    $this->logger->info('Loaded message.');
                    $onEmojiReactionEvent($this->processMessageReaction($reaction));
                });
            }
        );
    }

    private function processMessageReaction(MessageReaction $reaction): DiscordMessage
    {
        $convertedReactionsArray = $reaction->message
            ->reactions
            ->map(fn(Reaction $reaction) => new DiscordReaction(
                DiscordReactionEmoji::byString($reaction->emoji->name),
                DiscordReactionCount::byInt($reaction->count),
            ))
            ->toArray();
        $convertedAttachmentArray = SnList::byArray($reaction->message->attachments)
            ->map(
                fn(string $rawAttachment) => new DiscordAttachment(
                    DiscordAttachmentUrl::byString($rawAttachment)
                )
            )
            ->toArray();

        return new DiscordMessage(
            DiscordChannelId::byString($reaction->channel_id),
            DiscordMessageContent::byString($reaction->message->content),
            DiscordMessageId::byString($reaction->message_id),
            new DiscordMessageAuthor(
                DiscordMessageAuthorId::byString($reaction->message->author->id),
                DiscordMessageAuthorName::byString($reaction->message->author->username),
            ),
            DiscordAttachmentList::byArray($convertedAttachmentArray),
            DiscordReactionList::byArray($convertedReactionsArray),
        );
    }
}
