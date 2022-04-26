<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Trello;

class TrelloTask
{
    public readonly TrelloTaskId $trelloTaskId;
    public readonly TrelloTaskName $trelloTaskName;
    public readonly TrelloTaskDescription $trelloTaskDescription;
    public readonly ?TrelloTaskDueDate $trelloTaskDueDate;
    public readonly TrelloTaskUrl $trelloTaskUrl;

    public function __construct(
        TrelloTaskId $trelloTaskId,
        TrelloTaskName $trelloTaskName,
        TrelloTaskDescription $trelloTaskDescription,
        ?TrelloTaskDueDate $trelloTaskDueDate,
        TrelloTaskUrl $trelloTaskUrl
    ) {
        $this->trelloTaskId = $trelloTaskId;
        $this->trelloTaskName = $trelloTaskName;
        $this->trelloTaskDescription = $trelloTaskDescription;
        $this->trelloTaskDueDate = $trelloTaskDueDate;
        $this->trelloTaskUrl = $trelloTaskUrl;
    }
}
