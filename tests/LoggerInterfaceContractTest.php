<?php

declare(strict_types=1);

namespace Psr\Log\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionMethod;

final class LoggerInterfaceContractTest extends TestCase
{
    public function testIsAnInterfaceWithTheNineSpecMethods(): void
    {
        $reflection = new ReflectionClass(LoggerInterface::class);

        $this->assertTrue($reflection->isInterface());

        $methods = array_map(
            static fn (ReflectionMethod $method): string => $method->getName(),
            $reflection->getMethods()
        );
        sort($methods);

        $this->assertSame(
            ['alert', 'critical', 'debug', 'emergency', 'error', 'info', 'log', 'notice', 'warning'],
            $methods
        );
    }

    /**
     * @return iterable<string, array{0: string}>
     */
    public function provideLevelMethodNames(): iterable
    {
        foreach (['alert', 'critical', 'debug', 'emergency', 'error', 'info', 'notice', 'warning'] as $name) {
            yield $name => [$name];
        }
    }

    /**
     * @dataProvider provideLevelMethodNames
     */
    public function testLevelMethodSignature(string $methodName): void
    {
        $method = new ReflectionMethod(LoggerInterface::class, $methodName);

        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
        $this->assertSame('void', $method->getReturnType()->getName());

        $parameters = $method->getParameters();
        $this->assertCount(2, $parameters);

        $message = $parameters[0];
        $this->assertSame('message', $message->getName());
        $this->assertFalse($message->isOptional());
        $this->assertFalse($message->isVariadic());
        $this->assertFalse($message->isPassedByReference());
        $messageType = $message->getType();
        $this->assertNotNull($messageType);
        $this->assertTrue($messageType->allowsNull() === false);
        $this->assertEqualsCanonicalizing(['string', 'Stringable'], $this->unionTypeNames($messageType));

        $context = $parameters[1];
        $this->assertSame('context', $context->getName());
        $this->assertTrue($context->isOptional());
        $this->assertSame('array', $context->getType()->getName());
        $this->assertIsArray($context->getDefaultValue());
        $this->assertSame([], $context->getDefaultValue());
    }

    public function testLogAcceptsAnUntypedLevelAndTheSameMessageAndContext(): void
    {
        $method = new ReflectionMethod(LoggerInterface::class, 'log');

        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
        $this->assertSame('void', $method->getReturnType()->getName());

        $parameters = $method->getParameters();
        $this->assertCount(3, $parameters);

        $level = $parameters[0];
        $this->assertSame('level', $level->getName());
        $this->assertFalse($level->isOptional());
        $this->assertNull($level->getType());

        $message = $parameters[1];
        $this->assertSame('message', $message->getName());
        $this->assertFalse($message->isOptional());
        $this->assertEqualsCanonicalizing(['string', 'Stringable'], $this->unionTypeNames($message->getType()));

        $context = $parameters[2];
        $this->assertSame('context', $context->getName());
        $this->assertTrue($context->isOptional());
        $this->assertSame('array', $context->getType()->getName());
        $this->assertSame([], $context->getDefaultValue());
    }

    public function testLogDocblockDeclaresInvalidArgumentException(): void
    {
        $docComment = (new ReflectionMethod(LoggerInterface::class, 'log'))->getDocComment();
        $this->assertIsString($docComment);

        $normalized = $this->normalizeDocblock($docComment);

        $this->assertMatchesRegularExpression(
            '/@throws\s+\\\\Psr\\\\Log\\\\InvalidArgumentException/u',
            $normalized
        );
    }

    public function testClassDocblockStatesPlaceholderFormAndExceptionKey(): void
    {
        $docComment = (new ReflectionClass(LoggerInterface::class))->getDocComment();
        $this->assertIsString($docComment);

        $normalized = $this->normalizeDocblock($docComment);

        $this->assertMatchesRegularExpression(
            '/placeholders in the form: \{foo\} where foo will be replaced by the context data in key "foo"\./u',
            $normalized
        );
        $this->assertMatchesRegularExpression(
            '/it MUST be in a key named "exception"\./u',
            $normalized
        );
    }

    /**
     * @return list<string>
     */
    private function unionTypeNames(\ReflectionType $type): array
    {
        $this->assertInstanceOf(\ReflectionUnionType::class, $type);
        $names = array_map(
            static fn (\ReflectionNamedType $named): string => $named->getName(),
            $type->getTypes()
        );
        sort($names);

        return $names;
    }

    private function normalizeDocblock(string $docComment): string
    {
        $lines = preg_split('/\R/u', $docComment) ?: [];
        $normalized = [];

        foreach ($lines as $line) {
            $line = preg_replace('/^\s*\*\s?/u', '', $line) ?? $line;
            $line = trim($line);
            if ($line === '' || $line === '/') {
                continue;
            }
            $normalized[] = $line;
        }

        return implode(' ', $normalized);
    }
}
