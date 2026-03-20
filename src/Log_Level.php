<?php

declare (strict_types=1);
namespace Psr\Log;

/**
 * Defines the eight RFC 5424 syslog severity levels used by PSR-3.
 *
 * Constants are ordered from most severe (EMERGENCY) to least severe (DEBUG).
 * Pass these constants as the $level argument to Logger_Interface::log() to
 * avoid magic strings and ensure your code uses only recognised PSR-3 levels.
 *
 * @since 1.0
 * @see https://tools.ietf.org/html/rfc5424 RFC 5424 §6.2.1 Severity levels
 */
class Log_Level
{
    /** System is unusable; requires immediate escalation. RFC 5424 severity 0. */
    public const EMERGENCY = 'emergency';

    /** Action must be taken immediately; RFC 5424 severity 1. */
    public const ALERT = 'alert';

    /** Critical conditions, subsystem failures; RFC 5424 severity 2. */
    public const CRITICAL = 'critical';

    /** Runtime errors that should be investigated; RFC 5424 severity 3. */
    public const ERROR = 'error';

    /** Warning conditions, potential problems; RFC 5424 severity 4. */
    public const WARNING = 'warning';

    /** Normal but significant events; RFC 5424 severity 5. */
    public const NOTICE = 'notice';

    /** Informational messages about normal operation; RFC 5424 severity 6. */
    public const INFO = 'info';

    /** Detailed debug information, typically disabled in production; RFC 5424 severity 7. */
    public const DEBUG = 'debug';
}