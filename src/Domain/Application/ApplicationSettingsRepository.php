<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

interface ApplicationSettingsRepository
{
    public function getApplicationSettings(): ApplicationSettings;
}
