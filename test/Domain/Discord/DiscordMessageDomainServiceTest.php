<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\Discord;

use Tumugin\Potapota\Domain\Discord\DiscordMessageDomainService;
use Tumugin\Potapota\Test\BaseTestCase;
use Tumugin\Potapota\Test\Mock\MockClickUpTask;
use Tumugin\Potapota\Test\Mock\MockDiscordMessage;

class DiscordMessageDomainServiceTest extends BaseTestCase
{
    private DiscordMessageDomainService $discordMessageDomainService;
    private MockDiscordMessage $mockDiscordMessage;
    private MockClickUpTask $mockClickUpTask;

    protected function setUp(): void
    {
        parent::setUp();
        $this->discordMessageDomainService = $this->make(DiscordMessageDomainService::class);
        $this->mockDiscordMessage = $this->make(MockDiscordMessage::class);
        $this->mockClickUpTask = $this->make(MockClickUpTask::class);
    }

    public function testCreateDiscordDraftMessageByClickUpTask(): void
    {
        $actual = $this->discordMessageDomainService
            ->createDiscordDraftMessageByClickUpTask(
                $this->mockDiscordMessage->createMockDiscordMessage(),
                $this->mockClickUpTask->createMockClickUpTask(),
            );

        $this->assertSame('test_channel_id', $actual->discordChannelId->toString());
        $this->assertSame(
            'タスクを作ったよ～～～！！！
タスクのタイトルは「藍井すずしか好きじゃないタスク」だよ～～～！
ちゃんとやらないとあおいすずに怒られるぞ～～

ClickUp: https://example.com/test/12345
元メッセージ: https://discord.com/channels/test_guild_id/test_channel_id/test_message_id',
            $actual->discordMessageContent->toString()
        );
    }
}
