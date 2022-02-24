<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Mock;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDescription;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskDueDate;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskId;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskName;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskUrl;

class MockClickUpTask
{
    public function createMockClickUpTask(): ClickUpTask
    {
        return new ClickUpTask(
            ClickUpTaskId::byString('aoisuzu12345'),
            ClickUpTaskName::byString('藍井すずしか好きじゃないタスク'),
            ClickUpTaskDescription::byString('藍井すずに真剣になってきちゃったあ'),
            ClickUpTaskDueDate::make('2021-12-07T00:00:00Z'),
            ClickUpTaskUrl::byString('https://example.com/test/12345')
        );
    }
}
