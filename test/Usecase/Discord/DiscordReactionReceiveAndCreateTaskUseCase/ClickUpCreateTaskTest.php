<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Usecase\Discord\DiscordReactionReceiveAndCreateTaskUseCase;

use Mockery;
use Tumugin\Potapota\Domain\ApplicationSettings\ApplicationSettings;
use Tumugin\Potapota\Domain\ClickUp\ClickUpAPIToken;
use Tumugin\Potapota\Domain\ClickUp\ClickUpListId;
use Tumugin\Potapota\Domain\ClickUp\ClickUpSetting;
use Tumugin\Potapota\Domain\ClickUp\ClickUpSettingMap;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDescription;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskId;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskName;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskRepository;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskUrl;
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
use Tumugin\Potapota\Domain\Discord\DiscordMessageRepository;
use Tumugin\Potapota\Domain\Discord\DiscordReaction;
use Tumugin\Potapota\Domain\Discord\DiscordReactionCount;
use Tumugin\Potapota\Domain\Discord\DiscordReactionEmoji;
use Tumugin\Potapota\Domain\Discord\DiscordReactionList;
use Tumugin\Potapota\Domain\Discord\DiscordToken;
use Tumugin\Potapota\Domain\Discord\DiscordTriggerEmoji;
use Tumugin\Potapota\Domain\Discord\MessageEventRepository;
use Tumugin\Potapota\Domain\TaskServiceSelection\TaskServiceSelection;
use Tumugin\Potapota\Domain\TaskServiceSelection\TaskServiceSelectionSettingMap;
use Tumugin\Potapota\Domain\Trello\TrelloSettingMap;
use Tumugin\Potapota\Test\BaseTestCase;
use Tumugin\Potapota\Usecase\Discord\DiscordReactionReceiveAndCreateTaskUseCase;

class ClickUpCreateTaskTest extends BaseTestCase
{
    private const MOCK_DISCORD_TRIGGER_EMOJI = 'unasuki';

    protected function setUp(): void
    {
        parent::setUp();

        $this->getContainer()->set(
            ApplicationSettings::class,
            new ApplicationSettings(
                DiscordToken::byString(''),
                DiscordTriggerEmoji::byString(self::MOCK_DISCORD_TRIGGER_EMOJI),
                new ClickUpSettingMap([
                    '12345' => new ClickUpSetting(
                        ClickUpAPIToken::byString('123'),
                        ClickUpListId::byString('123')
                    ),
                ]),
                null,
                new TaskServiceSelectionSettingMap([
                    '12345' => TaskServiceSelection::CLICKUP,
                ]),
                new TrelloSettingMap([])
            )
        );

        $this->getContainer()->set(
            MessageEventRepository::class,
            Mockery::mock(MessageEventRepository::class)
        );
    }

    public function testOnReceiveEmojiShouldCreateTask(): void
    {
        $mockClickUpTaskRepository = Mockery::mock(ClickUpTaskRepository::class);
        $mockClickUpTaskRepository
            ->shouldReceive('createClickUpTask')
            ->once()
            ->andReturn($this->createMockClickUpTask());
        $this->getContainer()->set(
            ClickUpTaskRepository::class,
            $mockClickUpTaskRepository
        );

        $mockDiscordMessageRepository = Mockery::mock(DiscordMessageRepository::class);
        $mockDiscordMessageRepository
            ->shouldReceive('createMessage')
            ->once();
        $this->getContainer()->set(
            DiscordMessageRepository::class,
            $mockDiscordMessageRepository
        );

        $useCase = $this->make(DiscordReactionReceiveAndCreateTaskUseCase::class);
        $useCase->onReceiveEmoji(
            $this->createMockDiscordMessageShouldCreateTask()
        );
    }

    private function createMockClickUpTask(): ClickUpTask
    {
        return new ClickUpTask(
            ClickUpTaskId::byString('aaaa'),
            ClickUpTaskName::byString('???'),
            ClickUpTaskDescription::byString('?????????'),
            null,
            ClickUpTaskUrl::byString('https://example.com/test/12345')
        );
    }

    private function createMockDiscordMessageShouldCreateTask(): DiscordMessage
    {
        return new DiscordMessage(
            DiscordChannelId::byString('test_channel_id'),
            DiscordMessageContent::byString('????????????????????????????????????'),
            DiscordMessageId::byString('test_message_id'),
            new DiscordMessageAuthor(
                DiscordMessageAuthorId::byString('test_user_id'),
                DiscordMessageAuthorName::byString('?????????????????????????????????????????????')
            ),
            DiscordAttachmentList::byArray([
                new DiscordAttachment(
                    DiscordAttachmentUrl::byString('https://example.com/test')
                )
            ]),
            DiscordReactionList::byArray([
                new DiscordReaction(
                    DiscordReactionEmoji::byString(self::MOCK_DISCORD_TRIGGER_EMOJI),
                    DiscordReactionCount::byInt(1)
                )
            ]),
            DiscordGuildId::byString('12345')
        );
    }
}
