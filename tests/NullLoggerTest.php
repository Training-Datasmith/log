<?php

declare(strict_types=1);

namespace Psr\Log\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\Log\Tests\Fixtures\StringableProbe;

final class NullLoggerTest extends TestCase
{
    public function testIsAConcreteLoggerWithAPublicNullConstructor(): void
    {
        $logger = new NullLogger();

        $this->assertInstanceOf(NullLogger::class, $logger);
        $this->assertInstanceOf(AbstractLogger::class, $logger);
        $this->assertInstanceOf(LoggerInterface::class, $logger);

        $reflection = new \ReflectionClass(NullLogger::class);
        $this->assertFalse($reflection->isAbstract());
        $this->assertTrue($reflection->isInstantiable());
        $constructor = $reflection->getConstructor();
        $this->assertTrue($constructor === null || $constructor->getNumberOfRequiredParameters() === 0);
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public function provideLevelMethods(): iterable
    {
        foreach (['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'] as $method) {
            yield $method => [$method];
        }
    }

    /**
     * @dataProvider provideLevelMethods
     */
    public function testLevelMethodDiscardsTheRecord(string $method): void
    {
        $logger = new NullLogger();
        error_clear_last();

        ob_start();
        $return = $logger->{$method}('msg', ['exception' => new \RuntimeException('x')]);
        $output = ob_get_clean();

        $this->assertIsString($output);
        $this->assertSame('', $output);
        $this->assertNull($return);
        $this->assertNull(error_get_last());
    }

    public function testLogWithEachRfc5424ConstantDiscardsTheSameWay(): void
    {
        $constants = (new \ReflectionClass(LogLevel::class))->getConstants();

        foreach ($constants as $level) {
            $logger = new NullLogger();
            error_clear_last();

            ob_start();
            $return = $logger->log($level, 'm', ['a' => 1]);
            $output = ob_get_clean();

            $this->assertIsString($output);
            $this->assertSame('', $output);
            $this->assertNull($return);
            $this->assertNull(error_get_last());
        }
    }

    public function testStringableMessageIsAcceptedAndNotCast(): void
    {
        $logger = new NullLogger();
        $probe = new StringableProbe();
        error_clear_last();

        ob_start();
        $return = $logger->warning($probe, []);
        $output = ob_get_clean();

        $this->assertNull($return);
        $this->assertSame(0, $probe->castCount);
        $this->assertIsString($output);
        $this->assertSame('', $output);
        $this->assertNull(error_get_last());
    }

    public function testMixedContextIsLeftUntouched(): void
    {
        $logger = new NullLogger();
        $open = fopen('php://memory', 'r');
        $closed = fopen('php://memory', 'r');
        fclose($closed);

        $context = [
            'null' => null,
            'bool' => true,
            'int' => 0,
            'float' => 0.5,
            'string' => 's',
            'nested' => ['a' => 1],
            'datetime' => new \DateTimeImmutable('2020-01-02T03:04:05+00:00'),
            'resource' => $open,
            'closed' => $closed,
            'exception' => new \LogicException('Fail'),
        ];

        error_clear_last();
        ob_start();
        $firstReturn = $logger->error('one', $context);
        $firstOutput = ob_get_clean();
        $this->assertNull($firstReturn);
        $this->assertSame('', $firstOutput);
        $this->assertNull(error_get_last());

        error_clear_last();
        ob_start();
        $secondReturn = $logger->error('two', ['exception' => 'oops']);
        $secondOutput = ob_get_clean();
        $this->assertNull($secondReturn);
        $this->assertSame('', $secondOutput);
        $this->assertNull(error_get_last());

        if (is_resource($open)) {
            fclose($open);
        }
    }
}
