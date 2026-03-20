<?php

declare(strict_types=1);

/**
 * Example: Using PSR-3 Logger_Interface for interoperable logging.
 *
 * Type-hint Logger_Interface so your library works with Monolog, Analog,
 * or any other PSR-3 compliant logger without modification.
 */

use Psr\Log\Logger_Interface;
use Psr\Log\Log_Level;
use Psr\Log\Null_Logger;

// --- A library service that logs without coupling to Monolog ---

final class User_Registration_Service
{
    public function __construct(
        private readonly Logger_Interface $logger = new Null_Logger(),
    ) {}

    public function register(string $email): bool
    {
        // Use {placeholder} tokens in messages — values come from context.
        $this->logger->info('Registration attempt started', ['email' => $email]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->logger->warning(
                'Registration rejected: invalid email {email}',
                ['email' => $email],
            );
            return false;
        }

        try {
            // Simulate DB insert...
            $this->logger->info('User registered successfully', ['email' => $email]);
            return true;
        } catch (\Throwable $e) {
            // Exceptions go in the "exception" key so handlers can format the trace.
            $this->logger->error(
                'Failed to register user {email}',
                ['email' => $email, 'exception' => $e],
            );
            return false;
        }
    }
}

// --- Simple stderr logger for illustration ---

final class Stderr_Logger extends \Psr\Log\Abstract_Logger
{
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        // Interpolate {placeholder} tokens from context.
        $interpolated = preg_replace_callback(
            '/\{(\w+)\}/',
            fn($m) => isset($context[$m[1]]) ? (string) $context[$m[1]] : $m[0],
            (string) $message,
        );

        fwrite(STDERR, sprintf("[%s] %s\n", strtoupper((string) $level), $interpolated));
    }
}

// --- Usage ---

// With no logger (Null_Logger default): registration runs silently.
$service = new User_Registration_Service();
$service->register('alice@example.com');

// With a real logger: messages appear on stderr.
$service = new User_Registration_Service(new Stderr_Logger());
$service->register('alice@example.com');
// [INFO] Registration attempt started
// [INFO] User registered successfully

$service->register('not-an-email');
// [INFO] Registration attempt started
// [WARNING] Registration rejected: invalid email not-an-email

// --- Using Log_Level constants directly ---

$logger = new Stderr_Logger();
$logger->log(Log_Level::DEBUG, 'Cache miss for key {key}', ['key' => 'user_42']);
// [DEBUG] Cache miss for key user_42
