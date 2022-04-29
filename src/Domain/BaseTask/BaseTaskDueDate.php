<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\BaseTask;

use Carbon\Carbon;
use Tumugin\Stannum\SnString;

class BaseTaskDueDate extends Carbon
{
    public function formatToShortDate(): SnString
    {
        return SnString::byString(
            $this->format('m/d')
        );
    }
}
