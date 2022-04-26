<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Usecase\Discord\DiscordReactionReceiveAndCreateTaskUseCase;

use Mockery;
use Tumugin\Potapota\Domain\ApplicationSettings\ApplicationSettings;
use Tumugin\Potapota\Domain\ClickUp\ClickUpSettingMap;
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
use Tumugin\Potapota\Domain\Trello\TrelloAPIKey;
use Tumugin\Potapota\Domain\Trello\TrelloAPIToken;
use Tumugin\Potapota\Domain\Trello\TrelloListId;
use Tumugin\Potapota\Domain\Trello\TrelloSetting;
use Tumugin\Potapota\Domain\Trello\TrelloSettingMap;
use Tumugin\Potapota\Domain\Trello\TrelloTask;
use Tumugin\Potapota\Domain\Trello\TrelloTaskDescription;
use Tumugin\Potapota\Domain\Trello\TrelloTaskId;
use Tumugin\Potapota\Domain\Trello\TrelloTaskName;
use Tumugin\Potapota\Domain\Trello\TrelloTaskRepository;
use Tumugin\Potapota\Domain\Trello\TrelloTaskUrl;
use Tumugin\Potapota\Test\BaseTestCase;
use Tumugin\Potapota\Usecase\Discord\DiscordReactionReceiveAndCreateTaskUseCase;

class TrelloCreateTaskTest extends BaseTestCase
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
                new ClickUpSettingMap([]),
                null,
                new TaskServiceSelectionSettingMap([
                    '12345' => TaskServiceSelection::TRELLO,
                ]),
                new TrelloSettingMap([
                    '12345' => new TrelloSetting(
                        TrelloAPIKey::byString('test'),
                        TrelloAPIToken::byString('test'),
                        TrelloListId::byString('test'),
                    ),
                ])
            )
        );

        $this->getContainer()->set(
            MessageEventRepository::class,
            Mockery::mock(MessageEventRepository::class)
        );
    }

    public function testOnReceiveEmojiShouldCreateTask(): void
    {
        $mockClickUpTaskRepository = Mockery::mock(TrelloTaskRepository::class);
        $mockClickUpTaskRepository
            ->shouldReceive('createTrelloTask')
            ->once()
            ->andReturn($this->createMockTrelloTask());
        $this->getContainer()->set(
            TrelloTaskRepository::class,
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

    private function createMockTrelloTask(): TrelloTask
    {
        return new TrelloTask(
            TrelloTaskId::byString(''),
            TrelloTaskName::byString(''),
            TrelloTaskDescription::byString(''),
            null,
            TrelloTaskUrl::byString('')
        );
    }

    private function createMockDiscordMessageShouldCreateTask(): DiscordMessage
    {
        return new DiscordMessage(
            DiscordChannelId::byString('test_channel_id'),
            DiscordMessageContent::byString('藍井すずしか好きじゃねぇ'),
            DiscordMessageId::byString('test_message_id'),
            new DiscordMessageAuthor(
                DiscordMessageAuthorId::byString('test_user_id'),
                DiscordMessageAuthorName::byString('藍井すずしか好きじゃないオタク')
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
