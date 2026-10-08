<?php

declare(strict_types=1);

namespace Psr\Log\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\Tests\Fixtures\AbstractRecorder;
use ReflectionClass;

final class AbstractLoggerTest extends TestCase
{
    public function testIsAbstractImplementsLoggerInterfaceAndUsesTheTrait(): void
    {
        $reflection = new ReflectionClass(AbstractLogger::class);

        $this->assertTrue($reflection->isAbstract());
        $this->assertContains(LoggerInterface::class, class_implements(AbstractLogger::class));
        $this->assertContains(LoggerTrait::class, $reflection->getTraitNames());

        $log = $reflection->getMethod('log');
        $this->assertTrue($log->isAbstract());
    }

    /**
     * @return iterable<string, array{0: string, 1: string, 2: int}>
     */
    public function provideLevelMethods(): iterable
    {
        yield 'emergency' => ['emergency', LogLevel::EMERGENCY, 1];
        yield 'alert' => ['alert', LogLevel::ALERT, 2];
        yield 'critical' => ['critical', LogLevel::CRITICAL, 3];
        yield 'error' => ['error', LogLevel::ERROR, 4];
        yield 'warning' => ['warning', LogLevel::WARNING, 5];
        yield 'notice' => ['notice', LogLevel::NOTICE, 6];
        yield 'info' => ['info', LogLevel::INFO, 7];
        yield 'debug' => ['debug', LogLevel::DEBUG, 8];
    }

    /**
     * @dataProvider provideLevelMethods
     */
    public function testInheritedLevelMethodsReachSubclassLog(string $method, string $expectedLevel, int $contextMarker): void
    {
        $recorder = new AbstractRecorder();
        $context = ['n' => $contextMarker];

        $recorder->{$method}('via-abstract', $context);

        $this->assertCount(1, $recorder->calls);
        [$argCount, $level, $message, $recordedContext] = $recorder->calls[0];
        $this->assertSame(3, $argCount);
        $this->assertSame($expectedLevel, $level);
        $this->assertSame('via-abstract', $message);
        $this->assertSame($context, $recordedContext);
    }
}
