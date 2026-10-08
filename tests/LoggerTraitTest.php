<?php

declare(strict_types=1);

namespace Psr\Log\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Psr\Log\LoggerTrait;
use Psr\Log\Tests\Fixtures\StringableProbe;
use Psr\Log\Tests\Fixtures\TraitRecorder;
use ReflectionClass;
use ReflectionMethod;

final class LoggerTraitTest extends TestCase
{
    public function testIsATraitWhoseLogMethodIsAbstract(): void
    {
        $reflection = new ReflectionClass(LoggerTrait::class);

        $this->assertTrue($reflection->isTrait());

        $log = new ReflectionMethod(LoggerTrait::class, 'log');
        $this->assertTrue($log->isAbstract());
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public function provideLevelMethods(): iterable
    {
        yield 'emergency' => ['emergency', LogLevel::EMERGENCY];
        yield 'alert' => ['alert', LogLevel::ALERT];
        yield 'critical' => ['critical', LogLevel::CRITICAL];
        yield 'error' => ['error', LogLevel::ERROR];
        yield 'warning' => ['warning', LogLevel::WARNING];
        yield 'notice' => ['notice', LogLevel::NOTICE];
        yield 'info' => ['info', LogLevel::INFO];
        yield 'debug' => ['debug', LogLevel::DEBUG];
    }

    /**
     * @dataProvider provideLevelMethods
     */
    public function testLevelMethodForwardsLevelMessageAndContext(string $method, string $expectedLevel): void
    {
        $recorder = new TraitRecorder();
        $object = new \stdClass();
        $context = ['user' => 'Ada', 'obj' => $object];

        $recorder->{$method}('hello', $context);

        $this->assertCount(1, $recorder->calls);
        [$argCount, $level, $message, $recordedContext] = $recorder->calls[0];
        $this->assertSame(3, $argCount);
        $this->assertSame($expectedLevel, $level);
        $this->assertSame('hello', $message);
        $this->assertSame($context, $recordedContext);
    }

    public function testOmittedContextIsStillPassedAsAThirdArgument(): void
    {
        $recorder = new TraitRecorder();

        $recorder->debug('x');

        $this->assertCount(1, $recorder->calls);
        [$argCount, , , $context] = $recorder->calls[0];
        $this->assertSame(3, $argCount);
        $this->assertSame([], $context);
    }

    public function testLevelMethodsDoNotCastStringableMessages(): void
    {
        $recorder = new TraitRecorder();
        $probe = new StringableProbe();

        $recorder->info($probe, ['k' => 1]);

        $this->assertCount(1, $recorder->calls);
        [, , $message] = $recorder->calls[0];
        $this->assertSame($probe, $message);
        $this->assertSame(0, $probe->castCount);
    }
}
