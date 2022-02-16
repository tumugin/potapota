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

class ClickUpTaskRepositoryImpl implements ClickUpTaskRepository
{
    private Client $client;
    private ApplicationSettings $applicationSettings;

    public function __construct(Client $client, ApplicationSettings $applicationSettings)
    {
        $this->client = $client;
        $this->applicationSettings = $applicationSettings;
    }

    public function createClickUpTask(ClickUpDraftTask $clickUpDraftTask): ClickUpTask
    {
        $taskList = new TaskList(
            $this->client,
            [
                'id' => $this->applicationSettings->getClickUpListId()->toString(),
                'name' => '',
            ]
        );
        $createdRawTask = $taskList->createTask([
            'name' => $clickUpDraftTask->getClickUpTaskName()->toString(),
            'description' => $clickUpDraftTask->getClickUpTaskDescription()->toString(),
            'due_date' => $clickUpDraftTask->getClickUpTaskDueDate()?->unix(),
        ]);

        return new ClickUpTask(
            ClickUpTaskId::byString($createdRawTask['id']),
            ClickUpTaskName::byString($createdRawTask['name']),
            ClickUpTaskDescription::byString($createdRawTask['description']),
            $createdRawTask['due_date'] !== null ? ClickUpTaskDueDate::createFromTimestampUTC(
                $createdRawTask['due_date']
            ) : null,
            ClickUpTaskUrl::byString($createdRawTask['url'])
        );
    }
}
