<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Mock;

use Tumugin\Potapota\Domain\Trello\TrelloTask;
use Tumugin\Potapota\Domain\Trello\TrelloTaskDescription;
use Tumugin\Potapota\Domain\Trello\TrelloTaskDueDate;
use Tumugin\Potapota\Domain\Trello\TrelloTaskId;
use Tumugin\Potapota\Domain\Trello\TrelloTaskName;
use Tumugin\Potapota\Domain\Trello\TrelloTaskUrl;

class MockTrelloTask
{
    public function createMockTrelloTask(): TrelloTask
    {
        return new TrelloTask(
            TrelloTaskId::byString('test12345'),
            TrelloTaskName::byString('藍井すずに真剣になる'),
            TrelloTaskDescription::byString('あおいすずちゃん今日もかわいいね'),
            TrelloTaskDueDate::parse('2022-04-30T19:00:00+09:00') ?: null,
            TrelloTaskUrl::byString('https://example.com/test12345task')
        );
    }
}
