<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PayPal;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $app = $e->getParam('application');
        $locator = $app->getServiceManager();;
        
        // Inject a zf2 style configuration into the PayPayl ConfigManager
        $config = $locator->get('config');
        $config = $config['paypal'];
        
        $paypalConfig = \PPConfigManager::getInstance();
        $reflection = new \ReflectionObject($paypalConfig);
        $configProperty = $reflection->getProperty('config');
        $configProperty->setAccessible(true);
        $configProperty->setValue($paypalConfig, $config);
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'wps_toolkit' => function ($sm) {
                    $config = $sm->get('config');
                    $config = $config['paypal']['wps_toolkit'];
                    $wpsToolkit = new \PayPal\Service\WPSToolkit($config); 
                    return $wpsToolkit;
                },
            ),    
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'paypalButton' => function ($sm) {
                    return new \PayPal\View\Helper\PayPalButton(
                        $sm->getServiceLocator()->get('wps_toolkit', false)
                    );
                },
            ),
        );
    }
}
