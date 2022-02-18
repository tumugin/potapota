<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Application;

use Psr\Log\LoggerInterface;
use Tumugin\Potapota\Domain\Application\ApplicationSettings;

class ErrorReporter
{
    private ApplicationSettings $applicationSettings;
    private LoggerInterface $logger;

    public function __construct(ApplicationSettings $applicationSettings, LoggerInterface $logger)
    {
        $this->applicationSettings = $applicationSettings;
        $this->logger = $logger;
    }

    public function registerErrorReporter(): void
    {
        if ($this->applicationSettings->sentryDsn === null) {
            $this->logger->warning('Sentry logger not registered. Please set SENTRY_DSN env value.');
            return;
        }

        \Sentry\init(['dsn' => $this->applicationSettings->sentryDsn->toString()]);

        $this->logger->info('Error reporter registered.');
    }
}
