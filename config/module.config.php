<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Log\Logger' => 'Zend\Log\LoggerServiceFactory',
            'PayPal\Service\IPN' => 'PayPal\Service\IPNServiceFactory',
            'PayPal\Service\ButtonService' => 'PayPal\Service\ButtonServiceFactory',
        ),
    ),
    
    'log' => array(
        'writers' => array(
            'db' => array(
                'name'     => 'Zend\Log\Writer\Null',
                'filters' => array(
                )
            )
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'ipn' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ipn',
                    'defaults' => array(
                        'controller' => 'ipn',
                    ),
                ),
            ),
         ),
    ),
    'controllers' => array(
        'invokables' => array(
            'ipn'       => 'PayPal\Controller\IpnController',
        ),
    ),
    
   'paypal' => array(
      //Account credentials
       'business'     => 'somebusiness',
       'environment'  => '',
       'notifyUrl'    => '', 
       'cacert'       => '',
    )
);
