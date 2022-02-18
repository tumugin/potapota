<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\ClickUp\Repository;

use ClickUp\Client;
use ClickUp\Objects\TaskList;
use Tumugin\Potapota\Domain\Application\ApplicationSettings;
use Tumugin\Potapota\Domain\ClickUp\ClickUpDraftTask;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDescription;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDueDate;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskId;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskName;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskRepository;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskUrl;
use Tumugin\Potapota\Domain\Discord\DiscordGuildId;

class ClickUpTaskRepositoryImpl implements ClickUpTaskRepository
{
    private ApplicationSettings $applicationSettings;

    public function __construct(ApplicationSettings $applicationSettings)
    {
        $this->applicationSettings = $applicationSettings;
    }

    public function createClickUpTask(DiscordGuildId $discordGuildId, ClickUpDraftTask $clickUpDraftTask): ClickUpTask
    {
        $client = new Client(
            $this->applicationSettings
                ->clickUpSettingMap
                ->getSettingByDiscordGuildId($discordGuildId)
                ->clickUpAPIToken->toString()
        );
        $taskList = new TaskList(
            $client,
            [
                'id' => $this->applicationSettings
                    ->clickUpSettingMap
                    ->getSettingByDiscordGuildId($discordGuildId)
                    ->clickUpListId->toString(),
                'name' => '',
            ]
        );
        $createdRawTask = $taskList->createTask([
            'name' => $clickUpDraftTask->getClickUpTaskName()->toString(),
            'description' => $clickUpDraftTask->getClickUpTaskDescription()->toString(),
            // NOTE: ミリ秒でのUNIX時間を指定する
            'due_date' => $clickUpDraftTask->getClickUpTaskDueDate()?->getPreciseTimestamp(3),
        ]);

        return new ClickUpTask(
            ClickUpTaskId::byString($createdRawTask['id']),
            ClickUpTaskName::byString($createdRawTask['task']['name']),
            ClickUpTaskDescription::byString($createdRawTask['task']['description']),
            $createdRawTask['task']['due_date'] !== null ? ClickUpTaskDueDate::createFromTimestampUTC(
                $createdRawTask['task']['due_date']
            ) : null,
            ClickUpTaskUrl::byString($createdRawTask['task']['url'])
        );
    }
}
