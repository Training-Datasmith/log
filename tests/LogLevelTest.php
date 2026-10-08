<?php

declare(strict_types=1);

namespace Psr\Log\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use ReflectionClass;

final class LogLevelTest extends TestCase
{
    public function testConstantsAreExactlyTheEightRfc5424Names(): void
    {
        $constants = (new ReflectionClass(LogLevel::class))->getConstants();

        $this->assertSame(
            [
                'EMERGENCY' => 'emergency',
                'ALERT' => 'alert',
                'CRITICAL' => 'critical',
                'ERROR' => 'error',
                'WARNING' => 'warning',
                'NOTICE' => 'notice',
                'INFO' => 'info',
                'DEBUG' => 'debug',
            ],
            $constants
        );
    }

    public function testClassIsAConcreteClassAndConstantsArePublic(): void
    {
        $reflection = new ReflectionClass(LogLevel::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isAbstract());

        foreach ($reflection->getReflectionConstants() as $constant) {
            $this->assertTrue($constant->isPublic());
        }
    }
}
