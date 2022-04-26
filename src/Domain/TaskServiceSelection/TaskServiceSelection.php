<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\TaskServiceSelection;

use Tumugin\Potapota\Domain\Exceptions\PotapotaUnexpectedConditionException;

enum TaskServiceSelection
{
    case TRELLO;
    case CLICKUP;

    public static function createByString(string $rawName): TaskServiceSelection
    {
        return match ($rawName) {
            'trello' | 'TRELLO' => self::TRELLO,
            'clickup' | 'CLICKUP' => self::CLICKUP,
            default => throw new PotapotaUnexpectedConditionException("Unknown task service {$rawName}"),
        };
    }
}
