<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\Discord;

use Tumugin\Potapota\Domain\Discord\DiscordMessageDomainService;
use Tumugin\Potapota\Test\BaseTestCase;
use Tumugin\Potapota\Test\Mock\MockClickUpTask;
use Tumugin\Potapota\Test\Mock\MockDiscordMessage;
use Tumugin\Potapota\Test\Mock\MockTrelloTask;

class DiscordMessageDomainServiceTest extends BaseTestCase
{
    private DiscordMessageDomainService $discordMessageDomainService;
    private MockDiscordMessage $mockDiscordMessage;
    private MockClickUpTask $mockClickUpTask;
    private MockTrelloTask $mockTrelloTask;

    protected function setUp(): void
    {
        parent::setUp();
        $this->discordMessageDomainService = $this->make(DiscordMessageDomainService::class);
        $this->mockDiscordMessage = $this->make(MockDiscordMessage::class);
        $this->mockClickUpTask = $this->make(MockClickUpTask::class);
        $this->mockTrelloTask = $this->make(MockTrelloTask::class);
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
            'ClickUpのタスクを作ったよ～～～！！！
ClickUpのタスクのタイトルは「藍井すずしか好きじゃないタスク」だよ～～～！
ちゃんとやらないとあおいすずに怒られるぞ～～

ClickUpタスク: https://example.com/test/12345
元メッセージ: https://discord.com/channels/test_guild_id/test_channel_id/test_message_id',
            $actual->discordMessageContent->toString()
        );
    }

    public function testCreateDiscordDraftMessageByTrelloTask(): void
    {
        $actual = $this->discordMessageDomainService
            ->createDiscordDraftMessageByTrelloTask(
                $this->mockDiscordMessage->createMockDiscordMessage(),
                $this->mockTrelloTask->createMockTrelloTask(),
            );

        $this->assertSame('test_channel_id', $actual->discordChannelId->toString());
        $this->assertSame(
            'Trelloのカードを作ったよ～～～！！！
Trelloのカードのタイトルは「藍井すずに真剣になる」だよ～～～！
ちゃんとやらないとあおいすずに怒られるぞ～～

Trelloカード: https://example.com/test12345task
元メッセージ: https://discord.com/channels/test_guild_id/test_channel_id/test_message_id',
            $actual->discordMessageContent->toString()
        );
    }
}
