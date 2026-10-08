<?php

declare(strict_types=1);

namespace Psr\Log\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;
use ReflectionClass;

final class InvalidArgumentExceptionTest extends TestCase
{
    public function testIsAnInstantiableSplInvalidArgumentException(): void
    {
        $reflection = new ReflectionClass(InvalidArgumentException::class);

        $this->assertFalse($reflection->isAbstract());
        $this->assertFalse($reflection->isInterface());
        $this->assertTrue(is_subclass_of(InvalidArgumentException::class, \InvalidArgumentException::class));
    }

    public function testConstructorKeepsMessageCodeAndPrevious(): void
    {
        $previous = new \RuntimeException('root');
        $exception = new InvalidArgumentException('bad level', 42, $previous);

        $this->assertSame('bad level', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
