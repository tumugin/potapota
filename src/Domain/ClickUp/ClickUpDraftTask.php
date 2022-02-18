<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

class ClickUpDraftTask
{
    public readonly ClickUpTaskDescription $clickUpTaskDescription;
    public readonly ?ClickUpTaskDueDate $clickUpTaskDueDate;
    public readonly ClickUpTaskName $clickUpTaskName;

    public function __construct(
        ClickUpTaskDescription $clickUpTaskDescription,
        ?ClickUpTaskDueDate $clickUpTaskDueDate,
        ClickUpTaskName $clickUpTaskName
    ) {
        $this->clickUpTaskDescription = $clickUpTaskDescription;
        $this->clickUpTaskDueDate = $clickUpTaskDueDate;
        $this->clickUpTaskName = $clickUpTaskName;
    }
}
