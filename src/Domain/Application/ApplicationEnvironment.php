<?php
// phpcs:ignoreFile

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

enum ApplicationEnvironment: string
{
    case PRODUCTION = 'production';
    case DEVELOPMENT = 'development';
    case TESTING = 'testing';
    case DEFAULT = '';

    public static function fromString(string $env): self
    {
        return match ($env) {
            'prod', 'production' => self::PRODUCTION,
            'develop', 'dev', 'development' => self::DEVELOPMENT,
            'test', 'testing' => self::TESTING,
            '' => self::DEFAULT,
            default => throw new \Exception('environment not found.'),
        };
    }

    public function toString(): string
    {
        return match ($this) {
            self::PRODUCTION => 'production',
            self::DEVELOPMENT => 'development',
            self::TESTING => 'testing',
            self::DEFAULT => '',
            default => throw new \Exception('environment not found.'),
        };
    }
}
