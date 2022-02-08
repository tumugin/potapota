<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

class ClickUpTask
{
    private ClickUpTaskId $clickUpTaskId;
    private ClickUpTaskName $clickUpTaskName;
    private ClickUpTaskDescription $clickUpTaskDescription;
    private ?ClickUpTaskDueDate $clickUpTaskDueDate;
    private ClickUpTaskUrl $clickUpTaskUrl;

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

    public function getClickUpTaskId(): ClickUpTaskId
    {
        return $this->clickUpTaskId;
    }

    public function getClickUpTaskName(): ClickUpTaskName
    {
        return $this->clickUpTaskName;
    }

    public function getClickUpTaskDescription(): ClickUpTaskDescription
    {
        return $this->clickUpTaskDescription;
    }

    public function getClickUpTaskDueDate(): ?ClickUpTaskDueDate
    {
        return $this->clickUpTaskDueDate;
    }

    public function getClickUpTaskUrl(): ClickUpTaskUrl
    {
        return $this->clickUpTaskUrl;
    }
}
