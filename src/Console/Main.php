<?php

namespace Tumugin\Potapota\Console;

use Psr\Log\LoggerInterface;

class Main
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info("Potapota started.");
    }
}
