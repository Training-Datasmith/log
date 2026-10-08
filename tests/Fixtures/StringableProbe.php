<?php

declare(strict_types=1);

namespace Psr\Log\Tests\Fixtures;

final class StringableProbe implements \Stringable
{
    public int $castCount = 0;

    public function __toString(): string
    {
        ++$this->castCount;

        return 'CAST';
    }
}
