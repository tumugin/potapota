<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\BaseTask;

use Carbon\Carbon;
use Tumugin\Stannum\SnString;

class BaseTaskDueDate extends Carbon
{
    public function formatToShortDateJST(): SnString
    {
        return SnString::byString(
            $this->setTimezone('Asia/Tokyo')->format('m/d')
        );
    }
}
