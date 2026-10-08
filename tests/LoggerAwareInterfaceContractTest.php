<?php

declare(strict_types=1);

namespace Psr\Log\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionMethod;

final class LoggerAwareInterfaceContractTest extends TestCase
{
    public function testOnlySetLoggerWithARequiredLoggerInterfaceArgument(): void
    {
        $reflection = new ReflectionClass(LoggerAwareInterface::class);

        $this->assertTrue($reflection->isInterface());

        $methods = array_map(
            static fn (ReflectionMethod $method): string => $method->getName(),
            $reflection->getMethods()
        );
        $this->assertSame(['setLogger'], $methods);

        $method = new ReflectionMethod(LoggerAwareInterface::class, 'setLogger');
        $this->assertTrue($method->isPublic());
        $this->assertSame('void', $method->getReturnType()->getName());

        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);

        $logger = $parameters[0];
        $this->assertSame('logger', $logger->getName());
        $this->assertFalse($logger->isOptional());
        $this->assertFalse($logger->allowsNull());
        $this->assertSame(LoggerInterface::class, $logger->getType()->getName());
    }
}
