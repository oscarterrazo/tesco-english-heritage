<?php

namespace TescoRedemptionPortal\Factory;

use TescoRedemptionPortal\Controller\TokenController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TokenControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $mVouchersService = $realServiceLocator->get('TescoRedemptionPortal\Interfaces\MVouchersInterface');
        return new TokenController($realServiceLocator, $mVouchersService);
    }
}