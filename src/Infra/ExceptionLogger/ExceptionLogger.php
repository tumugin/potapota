<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\ExceptionLogger;

use Monolog\Logger;

use function Sentry\captureException;

class ExceptionLogger
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function logExceptionError(\Exception $exception): void
    {
        $this->logger->error($exception);
        captureException($exception);
    }
}
