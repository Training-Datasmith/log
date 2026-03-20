<?php

declare (strict_types=1);
namespace Psr\Log;

/**
 * Convenience trait for classes that cannot extend Abstract_Logger.
 *
 * Include this trait in a class that already extends another base class but
 * still needs to implement Logger_Interface. It provides concrete implementations
 * of all eight level-specific methods by delegating to log(), which the using
 * class MUST implement as an abstract or concrete method.
 *
 * @since 1.0
 * @see Abstract_Logger For classes that CAN use inheritance instead of a trait.
 */
trait Logger_Trait
{
    /**
     * System is unusable.
     *
     * @param string|\Stringable $message Log message with optional {placeholder} tokens.
     * @param mixed[] $context Contextual data for interpolation and metadata.
     */
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log(Log_Level::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string|\Stringable $message Log message with optional {placeholder} tokens.
     * @param mixed[] $context Contextual data for interpolation and metadata.
     */
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log(Log_Level::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string|\Stringable $message Log message with optional {placeholder} tokens.
     * @param mixed[] $context Contextual data for interpolation and metadata.
     */
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log(Log_Level::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string|\Stringable $message Log message with optional {placeholder} tokens.
     * @param mixed[] $context Contextual data; use key "exception" for Throwable instances.
     */
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log(Log_Level::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string|\Stringable $message Log message with optional {placeholder} tokens.
     * @param mixed[] $context Contextual data about the warning condition.
     */
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log(Log_Level::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string|\Stringable $message Log message with optional {placeholder} tokens.
     * @param mixed[] $context Contextual data about the event.
     */
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log(Log_Level::NOTICE, $message, $context);
    }

    /**
     * Interesting events in normal application operation.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string|\Stringable $message Log message with optional {placeholder} tokens.
     * @param mixed[] $context Contextual data such as user ID or query text.
     */
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log(Log_Level::INFO, $message, $context);
    }

    /**
     * Detailed debug information, typically disabled in production.
     *
     * @param string|\Stringable $message Log message with optional {placeholder} tokens.
     * @param mixed[] $context Diagnostic data; may be verbose.
     */
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log(Log_Level::DEBUG, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * Classes using this trait MUST implement this method. It is the single
     * integration point that all level-specific methods delegate to.
     *
     * @param mixed $level A Log_Level constant string or custom level value.
     * @param string|\Stringable $message The log message.
     * @param mixed[] $context Contextual data.
     *
     * @throws \Psr\Log\InvalidArgumentException If $level is not recognised.
     */
    abstract public function log(mixed $level, string|\Stringable $message, array $context = []): void;
}