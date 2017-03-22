<?php

namespace TescoRedemptionPortal;

use TescoRedemptionPortal\Interfaces\EnvSettingsAwareInterface;
use TescoRedemptionPortal\Interfaces\LoggerAwareInterface;
use Zend\Log\Logger;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    /**
     * @var array
     */
    protected $envSettings;

    /**
     * @var Logger
     */
    protected $logger;

    public function getAutoloaderConfig()
    {

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        /** @var $serviceManager ServiceLocatorInterface */
        $serviceManager = $e->getApplication()->getServiceManager();

        // Load the environment settings
        $envSettings = $serviceManager->get('Config');
        $this->envSettings = $envSettings['env-settings'];

        $this->logger = $serviceManager->get('Zend\Log');
    }

    public function getControllerConfig()
    {
        return array(
            'initializers' => array(
                function ($instance) {
                    if ($instance instanceof EnvSettingsAwareInterface) {
                        $instance->setEnvSettings($this->envSettings);
                    }

                    if ($instance instanceof LoggerAwareInterface) {
                        $instance->setLogger($this->logger);
                    }
                }
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
                function ($instance) {
                    if ($instance instanceof EnvSettingsAwareInterface) {
                        $instance->setEnvSettings($this->envSettings);
                    }

                    if ($instance instanceof LoggerAwareInterface) {
                        $instance->setLogger($this->logger);
                    }
                }
            )
        );
    }
}