# Architecture: psr/log (PSR-3)

## Purpose

This package defines PSR-3: Logger Interface. It provides a standard contract
for logging libraries, enabling library and framework authors to write log calls
against a stable interface rather than coupling to a specific logging library
(Monolog, Analog, etc.).

## PSR Standard

**PSR-3** — https://www.php-fig.org/psr/psr-3/

## Directory Structure

```
src/
  Logger_Interface.php        — The eight-level logging contract
  Logger_Aware_Interface.php  — Marks objects that can receive a logger via injection
  Logger_Aware_Trait.php      — Default implementation of Logger_Aware_Interface
  Logger_Trait.php            — Trait providing all level methods delegating to log()
  Abstract_Logger.php         — Abstract base class using Logger_Trait
  Log_Level.php               — Constants for the eight RFC 5424 log level strings
  Invalid_Argument_Exception.php — Thrown when an unrecognised log level is used
  Null_Logger.php             — Null Object: discards all messages silently
```

## Key Design Decisions

### Eight fixed severity levels (RFC 5424)
Log levels mirror the syslog protocol: emergency, alert, critical, error, warning,
notice, info, debug. This gives consumers a universally understood severity
vocabulary. Custom levels are not defined by the standard.

### Single abstract method: log()
Implementations only MUST implement `log()`. The Logger_Trait provides all eight
level-specific convenience methods by delegating to log(). This means adding
a new level-specific method in the future does not break existing implementations.

### Context array for structured data
Every method accepts a `$context` array for structured key-value metadata.
Placeholders in the message ({key}) are interpolated from context. Exceptions
MUST be passed under the reserved "exception" key so log handlers can extract
and format the stack trace correctly.

### Null Object pattern
Null_Logger implements the full interface by doing nothing. This is the recommended
default for optional logger dependencies; it eliminates null guards and keeps
library code clean.

### Logger-aware injection
Logger_Aware_Interface and Logger_Aware_Trait provide the setter-injection pattern
for services that optionally accept a logger. This is useful when constructor
injection is not possible (e.g., framework-managed singletons).

## Extension Points

- Implement `Logger_Interface` (or extend `Abstract_Logger`) to build a new log handler.
- Decorate `Logger_Interface` to add filtering, sampling, context enrichment, or
  async buffering without changing the consuming code.
- Implement `Logger_Aware_Interface` on services that should optionally log.

## Dependency Flow

```
Application / library code
    └── Logger_Interface  (injected via constructor or set_logger())
            └── Log backend (Monolog, syslog, stderr, database, etc.)
```
