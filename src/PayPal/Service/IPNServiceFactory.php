<?php
namespace PayPal\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * A factory for creating the IPN service
 */
class IPNServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $ipnConfig = $config['paypal'];
        return new IPN($ipnConfig);
    }
}
