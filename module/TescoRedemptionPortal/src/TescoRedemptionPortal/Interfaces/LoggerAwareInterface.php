<?php

namespace TescoRedemptionPortal\Interfaces;

use Zend\Log\Logger;

interface LoggerAwareInterface
{
    /**
     * Set the logger
     *
     * @param Logger $logger
     */
    function setLogger(Logger $logger);
}