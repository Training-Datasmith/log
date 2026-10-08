<?php

declare(strict_types=1);

namespace Psr\Log\Tests\Fixtures;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

final class AwareHost implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}
