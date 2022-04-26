<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\BaseTask;

use Tumugin\Potapota\Domain\Discord\DiscordGuildId;
use Tumugin\Potapota\Domain\Exceptions\SettingException;

/**
 * @template T
 */
abstract class ServiceSettingMap
{
    /**
     * @var array<string|int, T> $values
     */
    private array $values;

    /**
     * @param array<string|int, T> $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @return T
     * @throws SettingException
     */
    public function getSettingByDiscordGuildId(DiscordGuildId $discordGuildId)
    {
        if (!isset($this->values[$discordGuildId->toString()])) {
            throw new SettingException("Setting for guildId {$discordGuildId} not found.");
        }

        return $this->values[$discordGuildId->toString()];
    }

    public function settingsOfDiscordGuildIdExists(DiscordGuildId $discordGuildId): bool
    {
        return isset($this->values[$discordGuildId->toString()]);
    }
}
