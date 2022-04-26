<?php

namespace Tumugin\Potapota\Domain\BaseTask;

use Tumugin\Stannum\SnInteger;
use Tumugin\Stannum\SnString;

class BaseTaskName extends SnString
{
    public function removeUrlsFromTaskName(): static
    {
        return $this->pregReplace(
            '/(https?|ftp):\/\/[^\s\/$.?#].[^\s]*/u',
            ''
        );
    }

    public function removeNewLineFromTaskName(): static
    {
        return $this->pregReplace(
            '/\R/u',
            ''
        );
    }

    public function shortenTaskName(): static
    {
        return $this->take(SnInteger::byInt(50));
    }

    public function addMudaiTextIfEmptyToTaskName(): static
    {
        $trimmedString = $this->trim();

        if ($trimmedString->isEmpty()) {
            return $this->trim()->concat('無題');
        } else {
            return $trimmedString;
        }
    }
}
