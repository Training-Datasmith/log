<?php

declare (strict_types=1);
namespace Psr\Log;

/**
 * Basic Implementation of LoggerAwareInterface.
 */
trait Logger_Aware_Trait
{
    /**
     * The logger instance.
     */
    protected ?Logger_Interface $logger = null;
    /**
     * Sets a logger.
     */
    public function set_logger(Logger_Interface $logger): void
    {
        $this->logger = $logger;
    }
}