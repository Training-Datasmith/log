<?php

declare (strict_types=1);
namespace Psr\Log;

/**
 * Abstract base class providing convenience implementations of all PSR-3 methods.
 *
 * Extend this class and implement only log() to get a fully functional logger.
 * All eight level-specific methods (emergency, alert, critical, etc.) are
 * provided by Logger_Trait and delegate to log() automatically.
 *
 * Use this when your logger class has no other parent. If it must extend
 * another class, use Logger_Trait directly instead.
 *
 * @since 1.0
 * @see Logger_Trait For use when inheritance from another class is required.
 */
abstract class Abstract_Logger implements Logger_Interface
{
    use Logger_Trait;
}