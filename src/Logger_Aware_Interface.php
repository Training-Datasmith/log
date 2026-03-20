<?php

declare (strict_types=1);
namespace Psr\Log;

/**
 * Describes a logger-aware instance.
 */
interface Logger_Aware_Interface
{
    /**
     * Sets a logger instance on the object.
     */
    public function set_logger(Logger_Interface $logger): void;
}