<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\Discord;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDescription;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDueDate;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskId;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskName;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskUrl;
use Tumugin\Potapota\Domain\Discord\DiscordMessageDomainService;
use Tumugin\Potapota\Test\BaseTestCase;
use Tumugin\Potapota\Test\Mock\MockDiscordMessage;

class DiscordMessageDomainServiceTest extends BaseTestCase
{
    private DiscordMessageDomainService $discordMessageDomainService;
    private MockDiscordMessage $mockDiscordMessage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->discordMessageDomainService = $this->getContainer()
            ->make(DiscordMessageDomainService::class);
        $this->mockDiscordMessage = $this->getContainer()
            ->make(MockDiscordMessage::class);
    }

    public function testCreateDiscordDraftMessageByClickUpTask()
    {
        $actual = $this->discordMessageDomainService
            ->createDiscordDraftMessageByClickUpTask(
                $this->mockDiscordMessage->createMockDiscordMessage(),
                new ClickUpTask(
                    ClickUpTaskId::byString('aoisuzu12345'),
                    ClickUpTaskName::byString('藍井すずしか好きじゃないタスク'),
                    ClickUpTaskDescription::byString('藍井すずに真剣になってきちゃったあ'),
                    ClickUpTaskDueDate::make('2021-12-07T00:00:00Z'),
                    ClickUpTaskUrl::byString('https://example.com/test/12345')
                ),
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
