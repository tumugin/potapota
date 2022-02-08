<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

interface ClickUpTaskRepository
{
    public function createClickUpTask(ClickUpDraftTask $clickUpDraftTask): ClickUpTask;
}
