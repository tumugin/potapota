<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Logger;

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerSettings
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function setup(): void
    {
        $stdoutHandler = new StreamHandler('php://stdout', Logger::DEBUG);
        $stderrHandler = new StreamHandler('php://stderr', Logger::WARNING);

        $colorLineFormatter = new ColoredLineFormatter();
        $stdoutHandler->setFormatter($colorLineFormatter);
        $stderrHandler->setFormatter($colorLineFormatter);

        $this->logger->pushHandler($stdoutHandler);
        $this->logger->pushHandler($stderrHandler);
    }
}
