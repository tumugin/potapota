<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

class ClickUpDraftTask
{
    private ClickUpTaskDescription $clickUpTaskDescription;
    private ClickUpTaskDueDate $clickUpTaskDueDate;
    private ClickUpTaskName $clickUpTaskName;

    public function __construct(
        ClickUpTaskDescription $clickUpTaskDescription,
        ClickUpTaskDueDate $clickUpTaskDueDate,
        ClickUpTaskName $clickUpTaskName
    ) {
        $this->clickUpTaskDescription = $clickUpTaskDescription;
        $this->clickUpTaskDueDate = $clickUpTaskDueDate;
        $this->clickUpTaskName = $clickUpTaskName;
    }

    public function getClickUpTaskDescription(): ClickUpTaskDescription
    {
        return $this->clickUpTaskDescription;
    }

    public function getClickUpTaskDueDate(): ClickUpTaskDueDate
    {
        return $this->clickUpTaskDueDate;
    }

    public function getClickUpTaskName(): ClickUpTaskName
    {
        return $this->clickUpTaskName;
    }
}
