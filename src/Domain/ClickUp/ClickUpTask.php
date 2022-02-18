<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

class ClickUpTask
{
    public readonly ClickUpTaskId $clickUpTaskId;
    public readonly ClickUpTaskName $clickUpTaskName;
    public readonly ClickUpTaskDescription $clickUpTaskDescription;
    public readonly ?ClickUpTaskDueDate $clickUpTaskDueDate;
    public readonly ClickUpTaskUrl $clickUpTaskUrl;

    public function __construct(
        ClickUpTaskId $clickUpTaskId,
        ClickUpTaskName $clickUpTaskName,
        ClickUpTaskDescription $clickUpTaskDescription,
        ?ClickUpTaskDueDate $clickUpTaskDueDate,
        ClickUpTaskUrl $clickUpTaskUrl
    ) {
        $this->clickUpTaskId = $clickUpTaskId;
        $this->clickUpTaskName = $clickUpTaskName;
        $this->clickUpTaskDescription = $clickUpTaskDescription;
        $this->clickUpTaskDueDate = $clickUpTaskDueDate;
        $this->clickUpTaskUrl = $clickUpTaskUrl;
    }
}
