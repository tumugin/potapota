<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\ClickUp;

use Carbon\Carbon;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDomainService;
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
use Tumugin\Potapota\Test\BaseTestCase;

class ClickUpTaskDomainServiceTest extends BaseTestCase
{
    private ClickUpTaskDomainService $clickUpTaskDomainService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clickUpTaskDomainService = $this->getContainer()
            ->make(ClickUpTaskDomainService::class);
    }

    public function testCreateClickUpDraftTaskByDiscordMessage(): void
    {
        Carbon::setTestNow(new Carbon('2021-12-07T00:00:00Z'));

        $actual = $this->clickUpTaskDomainService->createClickUpDraftTaskByDiscordMessage(
            new DiscordMessage(
                DiscordChannelId::byString('testchannel'),
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
                DiscordGuildId::byString('12345')
            )
        );

        $this->assertSame(
            '起票者: 藍井すず真剣オタク
藍井すずしか好きじゃねぇ

https://example.org/test.png
https://example.org/test2.png',
            $actual->getClickUpTaskDescription()->toString()
        );
        $this->assertSame(
            '2021-12-14T00:00:00+00:00',
            $actual->getClickUpTaskDueDate()->toIso8601String()
        );
        $this->assertSame(
            '藍井すずしか好きじゃねぇ',
            $actual->getClickUpTaskName()->toString()
        );
    }
}
