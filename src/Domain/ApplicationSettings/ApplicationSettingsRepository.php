<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\ApplicationSettings;

interface ApplicationSettingsRepository
{
    public function getApplicationSettings(): ApplicationSettings;
}
