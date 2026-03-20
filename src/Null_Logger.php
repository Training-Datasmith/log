<?php

declare (strict_types=1);
namespace Psr\Log;

/**
 * A no-operation logger that silently discards all log messages.
 *
 * Use Null_Logger as a default value for an optional Logger_Interface dependency.
 * This is the Null Object pattern applied to logging: it lets you call log methods
 * unconditionally without checking whether a real logger was provided, eliminating
 * scattered `if ($this->logger !== null)` guards throughout the codebase.
 *
 * Example:
 *   class MyService {
 *       public function __construct(
 *           private Logger_Interface $logger = new Null_Logger(),
 *       ) {}
 *   }
 *
 * @since 1.0
 */
class Null_Logger extends Abstract_Logger
{
    /**
     * Silently discards the log entry without writing it anywhere.
     *
     * All calls to this method are no-ops. No I/O is performed, no memory
     * is accumulated, and no exception is thrown regardless of $level.
     *
     * @param mixed $level A Log_Level constant or arbitrary level string (ignored).
     * @param string|\Stringable $message The log message (ignored).
     * @param mixed[] $context Contextual data (ignored).
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        // Intentional no-op: messages are discarded.
    }
}