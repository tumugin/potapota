<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\ClickUp\Repository;

use Tumugin\Potapota\Domain\ClickUp\ClickUpDraftTask;
use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskRepository;

class ClickUpTaskRepositoryImpl implements ClickUpTaskRepository
{
    public function createClickUpTask(ClickUpDraftTask $clickUpDraftTask): void
    {
        // TODO: Implement createClickUpTask() method.
    }
}
