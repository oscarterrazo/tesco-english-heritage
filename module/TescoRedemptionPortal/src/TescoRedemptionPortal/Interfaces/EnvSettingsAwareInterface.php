<?php

namespace TescoRedemptionPortal\Interfaces;

interface EnvSettingsAwareInterface
{
    /**
     * Set the environment settings
     *
     * @param $envSettings
     */
    function setEnvSettings($envSettings);
}