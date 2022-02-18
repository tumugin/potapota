<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\Discord;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDescription;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDueDate;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskId;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskName;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskUrl;
use Tumugin\Potapota\Domain\Discord\DiscordChannelId;
use Tumugin\Potapota\Domain\Discord\DiscordMessageDomainService;
use Tumugin\Potapota\Test\BaseTestCase;

class DiscordMessageDomainServiceTest extends BaseTestCase
{
    private DiscordMessageDomainService $discordMessageDomainService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->discordMessageDomainService = $this->getContainer()
            ->make(DiscordMessageDomainService::class);
    }

    public function testCreateDiscordDraftMessageByClickUpTask()
    {
        $actual = $this->discordMessageDomainService
            ->createDiscordDraftMessageByClickUpTask(
                DiscordChannelId::byString('test_channel'),
                new ClickUpTask(
                    ClickUpTaskId::byString('aoisuzu12345'),
                    ClickUpTaskName::byString('藍井すずしか好きじゃないタスク'),
                    ClickUpTaskDescription::byString('藍井すずに真剣になってきちゃったあ'),
                    ClickUpTaskDueDate::make('2021-12-07T00:00:00Z'),
                    ClickUpTaskUrl::byString('https://example.com/test/12345')
                ),
            );

        $this->assertSame('test_channel', $actual->discordChannelId->toString());
        $this->assertSame(
            'タスクを作成しました！
https://example.com/test/12345',
            $actual->discordMessageContent->toString()
        );
    }
}
