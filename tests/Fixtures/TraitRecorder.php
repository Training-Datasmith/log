<?php

declare(strict_types=1);

namespace Psr\Log\Tests\Fixtures;

use Psr\Log\LoggerTrait;

final class TraitRecorder
{
    use LoggerTrait;

    /** @var list<array{int, mixed, string|\Stringable, array}> */
    public array $calls = [];

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->calls[] = [func_num_args(), $level, $message, $context];
    }
}
