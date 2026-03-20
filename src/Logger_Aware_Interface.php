<?php

declare (strict_types=1);
namespace Psr\Log;

/**
 * Describes an object that is aware of a PSR-3 logger and can receive one.
 *
 * Implement this interface on services that optionally accept a logger.
 * The Logger_Aware_Trait provides a default implementation that simply stores
 * the logger in a protected property. Objects implementing this interface
 * SHOULD default to logging nothing (or using a Null_Logger) if set_logger()
 * has not been called.
 *
 * @since 1.0
 * @see Logger_Aware_Trait Default implementation via trait.
 */
interface Logger_Aware_Interface
{
    /**
     * Sets a PSR-3 logger instance on the object.
     *
     * Calling this method replaces any previously set logger. The object
     * SHOULD use the provided logger for all subsequent log calls. If this
     * method is never called, the object SHOULD default to a Null_Logger or
     * simply not log anything.
     *
     * @param Logger_Interface $logger The logger to use. Pass a Null_Logger
     *   to effectively disable logging without null-checks in calling code.
     *
     * @since 1.0
     */
    public function set_logger(Logger_Interface $logger): void;
}