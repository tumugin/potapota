<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Stannum\SnInteger;
use Tumugin\Stannum\SnString;

class ClickUpTaskName extends SnString
{
    public function removeUrlsFromTaskName(): static
    {
        return $this->pregReplace(
            SnString::byString('/(https?|ftp):\/\/[^\s\/$.?#].[^\s]*/u'),
            SnString::byString('')
        );
    }

    public function removeNewLineFromTaskName(): static
    {
        return $this->pregReplace(
            SnString::byString('/\R/u'),
            SnString::byString('')
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
            return $this->trim()->concat(SnString::byString('無題'));
        } else {
            return $trimmedString;
        }
    }
}
