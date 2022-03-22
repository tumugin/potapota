<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\ClickUp;

use Carbon\Carbon;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDomainService;
use Tumugin\Potapota\Test\BaseTestCase;
use Tumugin\Potapota\Test\Mock\MockDiscordMessage;

class ClickUpTaskDomainServiceTest extends BaseTestCase
{
    private ClickUpTaskDomainService $clickUpTaskDomainService;
    private MockDiscordMessage $mockDiscordMessage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clickUpTaskDomainService = $this->make(ClickUpTaskDomainService::class);
        $this->mockDiscordMessage = $this->make(MockDiscordMessage::class);
    }

    public function testCreateClickUpDraftTaskByDiscordMessage(): void
    {
        Carbon::setTestNow(new Carbon('2021-12-07T00:00:00Z'));

        $actual = $this->clickUpTaskDomainService->createClickUpDraftTaskByDiscordMessage(
            $this->mockDiscordMessage->createMockDiscordMessage()
        );

        $this->assertSame(
            '起票者: 藍井すず真剣オタク
メッセージ: https://discord.com/channels/test_guild_id/test_channel_id/test_message_id

藍井すずしか好きじゃねぇ

https://example.org/test.png
https://example.org/test2.png',
            $actual->clickUpTaskDescription->toString()
        );
        $this->assertSame(
            '2021-12-14T00:00:00+00:00',
            $actual->clickUpTaskDueDate?->toIso8601String()
        );
        $this->assertSame(
            '藍井すずしか好きじゃねぇ',
            $actual->clickUpTaskName->toString()
        );
    }
}
