<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Usecase\Discord;

use Mockery;
use Tumugin\Potapota\Domain\Application\ApplicationSettings;
use Tumugin\Potapota\Domain\Application\DiscordTriggerEmoji;
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
use Tumugin\Potapota\Domain\Discord\MessageEventRepository;
use Tumugin\Potapota\Test\BaseTestCase;
use Tumugin\Potapota\Usecase\Discord\DiscordReactionReceiveAndCreateTaskUseCase;

class DiscordReactionReceiveAndCreateTaskUseCaseTest extends BaseTestCase
{
    private const MOCK_DISCORD_TRIGGER_EMOJI = 'unasuki';

    protected function setUp(): void
    {
        parent::setUp();

        $mockApplicationSettings = Mockery::mock(ApplicationSettings::class);
        $mockApplicationSettings
            ->shouldReceive('getDiscordTriggerEmoji')
            ->andReturn(DiscordTriggerEmoji::byString(self::MOCK_DISCORD_TRIGGER_EMOJI));
        $this->getContainer()->set(
            ApplicationSettings::class,
            $mockApplicationSettings
        );

        $mockEventRepository = Mockery::mock(MessageEventRepository::class);
        $this->getContainer()->set(
            MessageEventRepository::class,
            $mockEventRepository
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

        $useCase = $this->getContainer()->make(DiscordReactionReceiveAndCreateTaskUseCase::class);
        $useCase->onReceiveEmoji(
            $this->createMockDiscordMessageShouldCreateTask()
        );
    }

    /**
     * @dataProvider providesMockDiscordMessageShouldNotCreateTask
     */
    public function testOnReceiveEmojiShouldNotCreateTask(DiscordMessage $discordMessage): void
    {
        $mockClickUpTaskRepository = Mockery::mock(ClickUpTaskRepository::class);
        $mockClickUpTaskRepository
            ->shouldReceive('createClickUpTask')
            ->never()
            ->andReturn($this->createMockClickUpTask());
        $this->getContainer()->set(
            ClickUpTaskRepository::class,
            $mockClickUpTaskRepository
        );

        $mockDiscordMessageRepository = Mockery::mock(DiscordMessageRepository::class);
        $mockDiscordMessageRepository
            ->shouldReceive('createMessage')
            ->never();
        $this->getContainer()->set(
            DiscordMessageRepository::class,
            $mockDiscordMessageRepository
        );

        $useCase = $this->getContainer()->make(DiscordReactionReceiveAndCreateTaskUseCase::class);
        $useCase->onReceiveEmoji(
            $discordMessage
        );
    }

    private function providesMockDiscordMessageShouldNotCreateTask(): array
    {
        return [
            [
                new DiscordMessage(
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
                            DiscordReactionCount::byInt(2)
                        )
                    ])
                )
            ],
            [
                new DiscordMessage(
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
                            DiscordReactionEmoji::byString('uooooooo'),
                            DiscordReactionCount::byInt(1)
                        )
                    ])
                )
            ],
            [
                new DiscordMessage(
                    DiscordChannelId::byString('test_channel_id'),
                    DiscordMessageContent::byString('藍井すずしか好きじゃねぇ'),
                    DiscordMessageId::byString('test_message_id'),
                    new DiscordMessageAuthor(
                        DiscordMessageAuthorId::byString('test_user_id'),
                        DiscordMessageAuthorName::byString('藍井すずしか好きじゃないオタク')
                    ),
                    DiscordAttachmentList::byArray([]),
                    DiscordReactionList::byArray([])
                )
            ],
        ];
    }

    private function createMockClickUpTask(): ClickUpTask
    {
        return new ClickUpTask(
            ClickUpTaskId::byString('aaaa'),
            ClickUpTaskName::byString('あ'),
            ClickUpTaskDescription::byString('あああ'),
            null,
            ClickUpTaskUrl::byString('https://example.com/test/12345')
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
            ])
        );
    }
}
