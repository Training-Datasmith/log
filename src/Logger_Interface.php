<?php

declare (strict_types=1);
namespace Psr\Log;

/**
 * Describes a PSR-3 compliant logger instance.
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data. The only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * Log levels are defined in RFC 5424 (Syslog Protocol) and represented
 * by the Log_Level class constants. They form a severity hierarchy from
 * most critical (emergency) to least critical (debug).
 *
 * @since 1.0
 * @see https://www.php-fig.org/psr/psr-3/
 * @see https://tools.ietf.org/html/rfc5424 RFC 5424 Syslog Protocol
 */
interface Logger_Interface
{
    /**
     * System is unusable.
     *
     * The most severe log level. Use only for conditions that indicate the
     * entire system is in an unrecoverable state and must be shut down immediately
     * (e.g., kernel panic, total storage failure, process manager crash).
     *
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Key-value pairs to interpolate into the message or
     *   attach as structured metadata. Use the key "exception" for Throwable instances.
     *
     * @since 1.0
     */
    public function emergency(string|\Stringable $message, array $context = []): void;

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger SMS alerts and wake on-call engineers. Less severe than emergency
     * in that the system can still operate in a degraded mode, but intervention
     * is required right now.
     *
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Contextual data. Use key "exception" for Throwable instances.
     *
     * @since 1.0
     */
    public function alert(string|\Stringable $message, array $context = []): void;

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception from a core
     * subsystem. The application continues but a significant function is broken.
     * Requires investigation but not necessarily immediate human intervention.
     *
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Contextual data. Use key "exception" for Throwable instances.
     *
     * @since 1.0
     */
    public function critical(string|\Stringable $message, array $context = []): void;

    /**
     * Runtime errors that do not require immediate action but should be logged
     * and monitored.
     *
     * Examples: a database query failed and was retried, a third-party API
     * returned an unexpected status. The request was served (possibly degraded)
     * but something went wrong that should be investigated.
     *
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Contextual data. Use key "exception" for Throwable instances.
     *
     * @since 1.0
     */
    public function error(string|\Stringable $message, array $context = []): void;

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong but indicate potential problems or
     * sub-optimal usage patterns worth reviewing.
     *
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Contextual data about the warning condition.
     *
     * @since 1.0
     */
    public function warning(string|\Stringable $message, array $context = []): void;

    /**
     * Normal but significant events.
     *
     * Examples: user logged out, a scheduled task started, a configuration
     * was reloaded. These are events that stand out in the log stream but are
     * not problems — they are noteworthy operational milestones.
     *
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Contextual data about the event.
     *
     * @since 1.0
     */
    public function notice(string|\Stringable $message, array $context = []): void;

    /**
     * Interesting events in the normal operation of the application.
     *
     * Example: User logs in, SQL queries, external API calls completed
     * successfully. Useful for auditing, analytics, and understanding the
     * flow of a request through the system.
     *
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Contextual data such as user ID, duration, or query text.
     *
     * @since 1.0
     */
    public function info(string|\Stringable $message, array $context = []): void;

    /**
     * Detailed debug information for diagnosing problems in development.
     *
     * Debug messages are typically disabled in production due to volume and
     * the sensitivity of data they may contain (variable values, stack frames,
     * intermediate computation results). They are enabled temporarily to trace
     * a specific issue.
     *
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Diagnostic data; may include variable dumps, timing,
     *   or other verbose information.
     *
     * @since 1.0
     */
    public function debug(string|\Stringable $message, array $context = []): void;

    /**
     * Logs a message with an arbitrary log level.
     *
     * This method is the single abstract method that concrete implementations
     * MUST implement. All other methods in this interface delegate to log().
     * The $level SHOULD be one of the Log_Level constants; implementations MUST
     * throw Invalid_Argument_Exception if $level is not a recognised level.
     *
     * @param mixed $level The log level. SHOULD be a Log_Level constant string
     *   (e.g., Log_Level::DEBUG). Custom levels are implementation-defined.
     * @param string|\Stringable $message The log message, optionally with {placeholder} tokens.
     * @param mixed[] $context Key-value pairs for message interpolation and
     *   structured metadata. Use key "exception" for Throwable instances.
     *
     * @throws \Psr\Log\InvalidArgumentException If $level is not a recognised
     *   log level and the implementation does not support custom levels.
     *
     * @since 1.0
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void;
}