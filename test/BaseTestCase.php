<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test;

use PHPUnit\Framework\TestCase;
use Tumugin\Potapota\DI\Container;

class BaseTestCase extends TestCase
{
    private \DI\Container $container;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        parent::setUp();
        putenv('ENV=testing');
        $this->container = Container::createContainer();
    }

    protected function getContainer(): \DI\Container
    {
        return $this->container;
    }
}
