<?php

declare(strict_types=1);

namespace Psr\Log\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\Log\Tests\Fixtures\AwareHost;
use ReflectionMethod;

final class LoggerAwareTraitTest extends TestCase
{
    public function testStartsNull(): void
    {
        $host = new AwareHost();

        $this->assertNull($host->getLogger());
    }

    public function testSetLoggerStoresThatInstance(): void
    {
        $host = new AwareHost();
        $logger = new NullLogger();

        $host->setLogger($logger);

        $this->assertSame($logger, $host->getLogger());
    }

    public function testASecondSetLoggerReplacesTheFirst(): void
    {
        $host = new AwareHost();
        $first = new NullLogger();
        $second = new NullLogger();

        $host->setLogger($first);
        $host->setLogger($second);

        $this->assertSame($second, $host->getLogger());
    }

    public function testSetLoggerDeclaresLoggerInterfaceOnTheParameter(): void
    {
        $parameter = (new ReflectionMethod(AwareHost::class, 'setLogger'))->getParameters()[0];
        $type = $parameter->getType();

        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame(LoggerInterface::class, $type->getName());
    }

    public function testSetLoggerRejectsANonLogger(): void
    {
        $host = new AwareHost();

        try {
            $host->setLogger(new \stdClass());
            $this->fail('Expected TypeError when passing a non-logger');
        } catch (\TypeError $exception) {
            $this->assertNull($host->getLogger());
        }
    }

    public function testSetLoggerReturnsNull(): void
    {
        $host = new AwareHost();

        $this->assertNull($host->setLogger(new NullLogger()));
    }

    public function testSetLoggerImplementationIsTheTraitMethod(): void
    {
        $host = new AwareHost();
        $this->assertInstanceOf(LoggerAwareInterface::class, $host);

        $file = (new ReflectionMethod(AwareHost::class, 'setLogger'))->getFileName();
        $this->assertIsString($file);
        $this->assertStringEndsWith('src/LoggerAwareTrait.php', $file);
    }
}
