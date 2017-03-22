<?php

namespace TescoRedemptionPortal\Processor;

use Zend\Log\Processor\ProcessorInterface;

/**
 * Class that adds the current APP_ENV variable to each log message
 *
 * @package TescoRedemptionPortal\Processor
 */
class EnvLogProcessor implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(array $event)
    {
        // Add the extra elements to the array if it doesn't already exist
        if (!isset($event['extra']) || !is_array($event['extra'])) {
            $event['extra'] = array();
        }

        $event['extra']['env'] = APP_ENV;

        return $event;
    }
}