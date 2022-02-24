<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Mock;

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

class MockDiscordMessage
{
    public function createMockDiscordMessage(): DiscordMessage
    {
        return new DiscordMessage(
            DiscordChannelId::byString('test_channel_id'),
            DiscordMessageContent::byString('藍井すずしか好きじゃねぇ'),
            DiscordMessageId::byString('test_message_id'),
            new DiscordMessageAuthor(
                DiscordMessageAuthorId::byString('test_author_id'),
                DiscordMessageAuthorName::byString('藍井すず真剣オタク')
            ),
            DiscordAttachmentList::byArray([
                new DiscordAttachment(
                    DiscordAttachmentUrl::byString('https://example.org/test.png')
                ),
                new DiscordAttachment(
                    DiscordAttachmentUrl::byString('https://example.org/test2.png')
                )
            ]),
            DiscordReactionList::byArray([
                new DiscordReaction(
                    DiscordReactionEmoji::byString('aoisuzu_shinken'),
                    DiscordReactionCount::byInt(2)
                )
            ]),
            DiscordGuildId::byString('test_guild_id')
        );
    }
}
