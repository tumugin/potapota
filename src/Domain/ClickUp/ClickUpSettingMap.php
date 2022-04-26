<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ClickUp;

use Tumugin\Potapota\Domain\Discord\DiscordGuildId;
use Tumugin\Potapota\Domain\Exceptions\SettingException;

class ClickUpSettingMap
{
    /**
     * @var array<string|int, ClickUpSetting> $values
     */
    private array $values;

    /**
     * @param array<string|int, ClickUpSetting> $values
     * @throws SettingException
     */
    public function __construct(array $values)
    {
        $this->values = $values;

        foreach ($values as $_ => $value) {
            if (!$value instanceof ClickUpSetting) {
                throw new SettingException('value of array not instance of ClickUpSetting');
            }
        }
    }

    public function getSettingByDiscordGuildId(DiscordGuildId $discordGuildId): ClickUpSetting
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
